<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Data statistik untuk dashboard BUMDES
        $stats = [
            'total_transactions' => 25, // Akan diambil dari database nanti
            'total_income' => 15000000,
            'total_expenses' => 8500000,
            'cash_balance' => 6500000,
        ];

        return view('dashboard', compact('stats'));
    }
}
