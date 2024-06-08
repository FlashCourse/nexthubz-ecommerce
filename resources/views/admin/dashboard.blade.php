<section class="py-5">
    <div class="row g-3">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="bg-primary text-white p-3 rounded shadow">
                <div class="h6">Total Orders</div>
                <div class="h5 fw-bolder">{{ $totalOrders }}</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="bg-success text-white p-3 rounded shadow">
                <div class="h6">Total Sales</div>
                <div class="h5 fw-bolder">${{ number_format($totalSales, 2) }}</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="bg-warning text-white p-3 rounded shadow">
                <div class="h6">Total Revenue</div>
                <div class="h5 fw-bolder">${{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="bg-danger text-white p-3 rounded shadow">
                <div class="h6">New Customers</div>
                <div class="h5 fw-bolder">{{ $newCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <div class="bg-white p-4 rounded shadow">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 font-weight-bold mb-0">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">View All Orders</a>
            </div>
            <div style="max-height: 300px; overflow-y: auto;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Date</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Total</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="h4 font-weight-bold mb-3">Notifications</h2>
            <p>No new notifications</p>
        </div>
    </div>
</section>
