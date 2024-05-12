<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        // Retrieve counts for different order statuses and total orders in a single query
        $orderCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Retrieve recent orders
        $recentOrders = Order::latest()->limit(5)->get(); // Assuming you want to display 5 recent orders

        // Pass the data to the view
        return view('user.dashboard', [
            'orderCounts' => $orderCounts,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function orders()
    {
        // logic to fetch user orders with pagination
        $orders = auth()->user()->orders()->paginate(10); // Paginate with 10 orders per page

        return view('user.orders', ['orders' => $orders]);
    }

    public function orderDetails($order)
    {
        // logic to fetch order details along with related order items
        $orderWithItems = Order::with('orderItems')->findOrFail($order);

        return view('user.orderDetails', ['order' => $orderWithItems]);
    }

    public function orderSuccess()
    {
        return view('order-success');
        if (session()->has('order_success')) {
            session()->forget('order_success');
            return view('order-success');
        } else {
            abort(404);
        }
    }

    public function orderFailure()
    {
        return view('order-failure');
        if (session()->has('order_failure')) {
            session()->forget('order_failure');
            return view('order-failure');
        } else {
            abort(404);
        }
    }
}
