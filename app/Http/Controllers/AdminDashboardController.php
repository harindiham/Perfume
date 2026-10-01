<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Perfume;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPerfumes = Perfume::count();

        $totalCategories = Category::count();

        $activePerfumes = Perfume::where('is_active', true)->count();

        $lowStockPerfumes = Perfume::where('stock', '<=', 5)->count();

        return view('admin.dashboard', [
            'totalPerfumes' => $totalPerfumes,
            'totalCategories' => $totalCategories,
            'activePerfumes' => $activePerfumes,
            'lowStockPerfumes' => $lowStockPerfumes,
        ]);
    }
}
