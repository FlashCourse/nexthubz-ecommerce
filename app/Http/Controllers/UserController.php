<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user(); // Get the authenticated user

        // Retrieve counts for different order statuses and total orders in a single query, filtered by user
        $orderCounts = Order::selectRaw('status, COUNT(*) as count')
            ->where('user_id', $user->id) // Filter by authenticated user's id
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Retrieve recent orders for the authenticated user
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get(); // Assuming you want to display 5 recent orders

        // Pass the data to the view
        return view('user.dashboard', [
            'orderCounts' => $orderCounts,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function orders()
    {
        // Logic to fetch user orders with pagination
        $orders = auth()->user()->orders()->paginate(10); // Paginate with 10 orders per page

        return view('user.orders', ['orders' => $orders]);
    }

    public function orderDetails($order)
    {
        $user = auth()->user(); // Get the authenticated user

        // Fetch order details along with related order items, variants, and attributes
        $orderWithItems = Order::with(['orderItems.variant.variantAttributes.attribute', 'orderItems.variant.variantAttributes.attributeValue'])
            ->where('user_id', $user->id) // Ensure the order belongs to the authenticated user
            ->findOrFail($order);

        // Prepare status colors
        $statusColors = $this->prepareStatusColors($orderWithItems->status);

        return view('user.orderDetails', ['order' => $orderWithItems, 'statusColors' => $statusColors]);
    }

    // HELPER FUNCTIONS
    private function prepareStatusColors($currentStatus)
    {
        return [
            'pending' => $this->getStatusColor($currentStatus, ['pending', 'processing', 'shipped', 'delivered']),
            'processing' => $this->getStatusColor($currentStatus, ['processing', 'shipped', 'delivered']),
            'shipped' => $this->getStatusColor($currentStatus, ['shipped', 'delivered']),
            'delivered' => $this->getStatusColor($currentStatus, ['delivered']),
        ];
    }

    private function getStatusColor($currentStatus, $checkStatus)
    {
        return in_array($currentStatus, $checkStatus) ? 'bg-orange-500' : 'bg-gray-500';
    }
    // END HELPER FUNCTIONS
}
