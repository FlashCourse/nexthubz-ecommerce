<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use OpenAdmin\Admin\Layout\Content;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use OpenAdmin\Admin\Admin;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        // Fetch data from models
        $totalOrders = Order::count();
        $totalSales = OrderItem::sum('price'); // Total sales value from OrderItems
        $totalRevenue = Payment::where('status', 'completed')->sum('amount'); // Total revenue from completed Payments
        $newCustomers = Order::distinct('customer_id')->count('customer_id');
        $recentOrders = Order::latest()->take(5)->get();

        $data = [
            'totalOrders' => $totalOrders,
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
            'newCustomers' => $newCustomers,
            'recentOrders' => $recentOrders,
        ];

        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            ->title('Dashboard')
            ->body(view('admin.dashboard', $data));
    }
}
