<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Product;
use App\Models\Purchases;
use App\Exports\LaporanPenjualanExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $products = Product::select('name', 'stock')->get();

        // ADMIN CHART DATA
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $dailyPurchases = Purchases::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $labels = [];
        $data = [];
        $current = $startOfMonth->copy();
        while ($current->lte($endOfMonth)) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->format('d M');
            $found = $dailyPurchases->firstWhere('date', $dateStr);
            $data[] = $found ? $found->total : 0;
            $current->addDay();
        }

        $purchases = [
            'labels' => $labels,
            'purchases_date' => $data
        ];

        // ===== TOTAL PENJUALAN HARI INI =====
        $todaySales = Purchases::whereDate('created_at', now())->count();

        // ===== FILTER PENJUALAN UNTUK USER =====
        $filter = $request->input('filter', 'daily');
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $date = $request->input('date', now()->toDateString()); // AMBIL DATE DARI INPUT

        if ($filter === 'previous_month') {
            $previousMonth = now()->subMonth();
            $month = $previousMonth->month;
            $year = $previousMonth->year;
        }

        $penjualans = collect();

        if ($filter === 'daily') {
            $penjualans = Purchases::with('user')
                ->whereDate('created_at', $date)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($filter === 'weekly') {
            $penjualans = Purchases::with('user')
                ->whereBetween('created_at', [now()->startOfWeek(), now()])
                ->orderByDesc('created_at')
                ->get();
        } elseif ($filter === 'monthly') {
            $penjualans = Purchases::with('user')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($filter === 'yearly') {
            $penjualans = Purchases::with('user')
                ->whereYear('created_at', $year)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($filter === 'previous_month') {
            $penjualans = Purchases::with('user')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('dashboard.index', compact(
            'products',
            'purchases',
            'todaySales',
            'filter',
            'penjualans',
            'month',
            'year',
            'date'
        ));
    }

    public function export(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $date = $request->input('date', now()->toDateString());
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        if ($filter === 'previous_month') {
            $previousMonth = now()->subMonth();
            $month = $previousMonth->month;
            $year = $previousMonth->year;
        }

        $purchases = collect();

        if ($filter === 'daily') {
            $purchases = Purchases::with('user')
                ->whereDate('created_at', $date)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($filter === 'weekly') {
            $purchases = Purchases::with('user')
                ->whereBetween('created_at', [now()->startOfWeek(), now()])
                ->orderByDesc('created_at')
                ->get();
        } elseif ($filter === 'monthly') {
            $purchases = Purchases::with('user')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($filter === 'yearly') {
            $purchases = Purchases::with('user')
                ->whereYear('created_at', $year)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($filter === 'previous_month') {
            $purchases = Purchases::with('user')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderByDesc('created_at')
                ->get();
        }

        return Excel::download(new LaporanPenjualanExport($purchases), 'laporan_penjualan.xlsx');
    }
}
