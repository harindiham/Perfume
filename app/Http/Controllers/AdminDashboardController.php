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

        $activePerfumes = Perfume::active()->count();

        $lowStockPerfumes = Perfume::lowStock()->count();

        return view('admin.dashboard', [
            'totalPerfumes' => $totalPerfumes,
            'totalCategories' => $totalCategories,
            'activePerfumes' => $activePerfumes,
            'lowStockPerfumes' => $lowStockPerfumes,
        ]);
    }
}