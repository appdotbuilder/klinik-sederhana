<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\InventoryItem;
use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the clinic dashboard.
     */
    public function index()
    {
        $today = today();
        
        // Today's statistics
        $todayVisits = Visit::today()->count();
        $todayRevenue = Bill::paid()->today()->sum('total');
        $todayPatients = Visit::today()->distinct('patient_id')->count();
        
        // Low stock items
        $lowStockItems = InventoryItem::lowStock()->get();
        
        // Top selling items (based on quantity sold)
        $topSellingItems = InventoryItem::select('inventory_items.*')
            ->selectRaw('COALESCE(SUM(bill_items.quantity), 0) as total_sold')
            ->leftJoin('bill_items', 'inventory_items.id', '=', 'bill_items.inventory_item_id')
            ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
            ->where(function ($query) {
                $query->where('bills.status', 'paid')
                      ->orWhereNull('bills.status');
            })
            ->groupBy('inventory_items.id')
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
        
        // Recent visits
        $recentVisits = Visit::with(['patient', 'bill'])
            ->latest('visit_date')
            ->limit(10)
            ->get();

        return Inertia::render('dashboard', [
            'stats' => [
                'todayVisits' => $todayVisits,
                'todayRevenue' => $todayRevenue,
                'todayPatients' => $todayPatients,
                'lowStockCount' => $lowStockItems->count(),
            ],
            'lowStockItems' => $lowStockItems,
            'topSellingItems' => $topSellingItems,
            'recentVisits' => $recentVisits,
        ]);
    }
}