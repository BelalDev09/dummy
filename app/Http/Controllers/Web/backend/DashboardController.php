<?php

namespace App\Http\Controllers\Web\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role_or_permission:admin|superadmin']);
    }

    /**
     * DASHBOARD
     */
    public function index()
    {
        $this->authorizeAdmin();

        return view('backend.dashboard', [
            'greeting' => $this->getGreeting(),
            'stats' => $this->getStats(),
            'recent_users' => $this->getRecentUsers(),
            'recent_orders' => $this->getRecentOrders(),
        ]);
    }

    /**
     * AUTH CHECK
     */
    private function authorizeAdmin()
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403, 'You do not have permission to access dashboard');
        }
    }

    /**
     * GREETING SYSTEM
     */
    private function getGreeting()
    {
        $hour = now()->hour;

        if ($hour < 12) {
            return [
                'message' => 'Good Morning',
                'icon' => 'ri-sun-line',
                'color' => 'text-warning'
            ];
        }

        if ($hour < 17) {
            return [
                'message' => 'Good Afternoon',
                'icon' => 'ri-sun-cloudy-line',
                'color' => 'text-primary'
            ];
        }

        return [
            'message' => 'Good Evening',
            'icon' => 'ri-moon-line',
            'color' => 'text-info'
        ];
    }

    /**
     * STATS (KPI)
     */
    private function getStats()
    {
        return [
            'total_users' => User::count(),

            'today_users' => User::whereDate('created_at', today())->count(),

            'total_products' => \App\Models\Product::count(),

            'total_orders' => Order::count(),

            'active_orders' => Order::whereIn('order_status', ['pending', 'processing'])->count(),

            'completed_orders' => Order::where('order_status', 'completed')->count(),
        ];
    }

    /**
     * RECENT USERS
     */
    private function getRecentUsers()
    {
        return User::latest()->take(5)->get();
    }

    /**
     * RECENT ORDERS
     */
    private function getRecentOrders()
    {
        return Order::latest()->take(5)->get();
    }
}
