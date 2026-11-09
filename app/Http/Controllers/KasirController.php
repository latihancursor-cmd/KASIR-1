<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DailyCapital;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KasirController extends Controller
{
    // ==================== DASHBOARD BERANDA ====================
    public function beranda()
    {
        $settings = StoreSetting::getSettings();
        
        $todayRevenue = Sale::whereDate('created_at', today())->sum('total_price');
        $todayCount = Sale::whereDate('created_at', today())->distinct('invoice_no')->count('invoice_no')
            + Sale::whereDate('created_at', today())->whereNull('invoice_no')->count();
        $itemsSold = Sale::whereDate('created_at', today())->sum('quantity');
        $todayProfit = Sale::whereDate('created_at', today())->sum('profit');
        $todayCashSales = Sale::whereDate('created_at', today())->where('payment_method', 'Tunai')->sum('total_price');
        $todayQrisSales = Sale::whereDate('created_at', today())->where('payment_method', 'QRIS')->sum('total_price');
        $todayDate = today()->format('Y-m-d');
        $dailyCapital = DailyCapital::whereDate('date', $todayDate)->first()
            ?? new DailyCapital([
                'date' => $todayDate,
                'initial_capital' => 0,
                'expenses' => 0,
            ]);
        $todayExpenses = (float) $dailyCapital->expenses;
        
        $lastTransactions = Sale::with('product')
            ->whereDate('created_at', today())
            ->latest()
            ->take(5)
            ->get();
        
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->count();
        
        $outOfStockProducts = Product::where('stock', 0)->count();
        
        return view('kasir.dashboard', compact(
            'settings',
            'todayRevenue',
            'todayCount',
            'itemsSold',
            'todayProfit',
            'todayCashSales',
            'todayQrisSales',
            'dailyCapital',
            'todayExpenses',
            'lastTransactions',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    public function dailyCapitalUpdate(Request $request)
    {
        $validated = $request->validate([
            'initial_capital' => 'required|numeric|min:0',
            'expenses' => 'required|numeric|min:0',
            'counted_cash' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $todayDate = today()->format('Y-m-d');
        $capital = DailyCapital::whereDate('date', $todayDate)->first();
        if (!$capital) {
            $capital = new DailyCapital(['date' => $todayDate]);
        }
        $capital->fill($validated);
        $capital->save();

        return redirect()->route('kasir.beranda')->with('success', 'Modal harian berhasil disimpan!');
    }

    // ==================== MANAJEMEN PRODUK ====================
    public function products(Request $request)
    {
        $query = Product::with('category');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $products = $query->latest()->get();
        $categories = Category::all();
        
        return view('kasir.products.index', compact('products', 'categories'));
    }

    public function productCreate()
    {
        $categories = Category::all();
        return view('kasir.products.create', compact('categories'));
    }

    public function generateProductSku(Request $request)
    {
        $request->validate(['category_id' => 'required|exists:categories,id']);

        return response()->json([
            'sku' => Product::generateSku($request->category_id),
        ]);
    }

    public function productStore(Request $request)
    {
        if (empty($request->sku) && $request->filled('category_id')) {
            $request->merge(['sku' => Product::generateSku($request->category_id)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        unset($validated['image']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('kasir.products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function productEdit(Product $product)
    {
        $categories = Category::all();
        return view('kasir.products.edit', compact('product', 'categories'));
    }

    public function productUpdate(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('kasir.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function productDestroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('kasir.products')->with('success', 'Produk berhasil dihapus!');
    }

    // ==================== MANAJEMEN STOK ====================
    public function stock(Request $request)
    {
        $query = Product::with('category');
        
        $filter = $request->get('filter', 'semua');
        
        if ($filter === 'menipis') {
            $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
        } elseif ($filter === 'habis') {
            $query->where('stock', 0);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        $products = $query->get();
        
        $totalProducts = Product::count();
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0)->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        $totalStockValue = Product::sum(DB::raw('stock * cost_price'));
        
        return view('kasir.stock.index', compact(
            'products',
            'filter',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'totalStockValue'
        ));
    }

    public function stockAdd(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product->increment('stock', $validated['quantity']);

        return back()->with('success', "Stok {$product->name} berhasil ditambah {$validated['quantity']} {$product->unit}!");
    }

    // ==================== RIWAYAT TRANSAKSI ====================
    public function history(Request $request)
    {
        $period = $request->get('period', 'hari_ini');
        
        $query = Sale::with(['product', 'user']);
        
        switch ($period) {
            case 'hari_ini':
                $query->whereDate('created_at', today());
                break;
            case '7_hari':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'bulan_ini':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'semua':
                // No filter
                break;
        }
        
        $sales = $query->latest()->get();
        $transactions = $sales->groupBy(function ($sale) {
            return $sale->invoice_no ?: 'sale-' . $sale->id;
        })->map(function ($items, $invoiceKey) {
            $first = $items->first();
            return [
                'invoice_no' => $first->invoice_no ?: ('TRX-' . $first->id),
                'created_at' => $first->created_at,
                'payment_method' => $first->payment_method ?? 'Tunai',
                'items' => $items->map(function ($sale) {
                    return [
                        'name' => $sale->product->name ?? 'Produk',
                        'unit' => $sale->product->unit ?? 'pcs',
                        'qty' => (int) $sale->quantity,
                        'price' => (float) ($sale->quantity ? $sale->total_price / $sale->quantity : 0),
                        'subtotal' => (float) $sale->total_price,
                    ];
                })->values(),
                'total' => (float) $items->sum('total_price'),
                'profit' => (float) $items->sum('profit'),
                'quantity' => (int) $items->sum('quantity'),
                'cash_paid' => (float) $items->sum('total_price'),
                'change' => 0,
            ];
        })->values();
        
        $totalRevenue = $sales->sum('total_price');
        $totalTransactions = $transactions->count();
        $totalItems = $sales->sum('quantity');
        $totalProfit = $sales->sum('profit');
        
        return view('kasir.history.index', compact(
            'transactions',
            'period',
            'totalRevenue',
            'totalTransactions',
            'totalItems',
            'totalProfit'
        ));
    }

    // ==================== PENGATURAN TOKO ====================
    public function settings()
    {
        $settings = StoreSetting::getSettings();
        $categories = Category::withCount('products')->get();
        
        return view('kasir.settings.index', compact('settings', 'categories'));
    }

    public function settingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'closing_reminder' => 'boolean',
            'closing_time' => 'nullable|string',
        ]);

        $validated['closing_reminder'] = $request->has('closing_reminder');

        $settings = StoreSetting::getSettings();
        $settings->update($validated);

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    // ==================== MANAJEMEN KATEGORI ====================
    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $validated['code'] = Category::generateCode($validated['name']);

        Category::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function categoryUpdate(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function categoryDestroy(Category $category)
    {
        // Set category_id to null for products in this category
        Product::where('category_id', $category->id)->update(['category_id' => null]);
        
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // ==================== LAPORAN PENJUALAN ====================
    public function reports(Request $request)
    {
        $period = $request->get('period', '7_hari');
        
        $query = Sale::with('product.category');
        
        switch ($period) {
            case '7_hari':
                $startDate = now()->subDays(7);
                $query->where('created_at', '>=', $startDate);
                break;
            case '30_hari':
                $startDate = now()->subDays(30);
                $query->where('created_at', '>=', $startDate);
                break;
            case 'bulan_ini':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                $startDate = now()->startOfMonth();
                break;
        }
        
        $sales = $query->get();
        
        $totalRevenue = $sales->sum('total_price');
        $totalProfit = $sales->sum('profit');
        $totalTransactions = $sales->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        // Daily revenue for chart
        $dailyRevenue = $sales->groupBy(function($sale) {
            return $sale->created_at->format('Y-m-d');
        })->map(function($daySales) {
            return $daySales->sum('total_price');
        });
        
        $bestDay = $dailyRevenue->sortDesc()->take(1);
        
        // Sales by category
        $salesByCategory = $sales->groupBy(function($sale) {
            return $sale->product->category->name ?? 'Lainnya';
        })->map(function($categorySales) {
            return $categorySales->sum('total_price');
        });
        
        // Top products
        $topProducts = $sales->groupBy('product_id')
            ->map(function($productSales) {
                return [
                    'product' => $productSales->first()->product,
                    'quantity' => $productSales->sum('quantity'),
                    'revenue' => $productSales->sum('total_price'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();
        
        // Stock status
        $totalStockValue = Product::sum(DB::raw('stock * cost_price'));
        $safeStock = Product::whereColumn('stock', '>', 'min_stock')->count();
        $lowStock = Product::whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0)->count();
        $outOfStock = Product::where('stock', 0)->count();
        
        $needRestock = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->take(5)
            ->get();
        
        return view('kasir.reports.index', compact(
            'period',
            'totalRevenue',
            'totalProfit',
            'totalTransactions',
            'averageTransaction',
            'dailyRevenue',
            'bestDay',
            'salesByCategory',
            'topProducts',
            'totalStockValue',
            'safeStock',
            'lowStock',
            'outOfStock',
            'needRestock'
        ));
    }

    // ==================== POS PENJUALAN ====================
    public function pos()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get()
            ->map(function ($product) {
                $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
                return $product;
            });
        
        $categories = Category::all();
        $settings = StoreSetting::getSettings();
        
        return view('kasir.pos', compact('products', 'categories', 'settings'));
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
        ]);

        $invoiceNo = 'TRX-' . str_pad(Sale::max('id') + 1, 4, '0', STR_PAD_LEFT);
        $totalAmount = 0;
        $itemsDetail = [];

        DB::transaction(function () use ($data, $invoiceNo, &$totalAmount, &$itemsDetail) {
            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = $item['quantity'];
                
                if ($product->stock < $qty) {
                    throw new \Exception("Stok tidak cukup untuk {$product->name}");
                }
                
                $product->decrement('stock', $qty);
                
                $totalPrice = $product->price * $qty;
                $totalCost = $product->cost_price * $qty;
                $profit = $totalPrice - $totalCost;
                
                Sale::create([
                    'invoice_no' => $invoiceNo,
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'cost_price' => $product->cost_price,
                    'total_price' => $totalPrice,
                    'profit' => $profit,
                    'payment_method' => $data['payment_method'] ?? 'Tunai',
                ]);

                $totalAmount += $totalPrice;
                $itemsDetail[] = [
                    'name' => $product->name,
                    'unit' => $product->unit,
                    'price' => $product->price,
                    'qty' => $qty,
                    'subtotal' => $totalPrice,
                ];
            }
        });

        $cashPaid = $data['cash_paid'] ?? $totalAmount;
        $change = max(0, $cashPaid - $totalAmount);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil!',
            'invoice_no' => $invoiceNo,
            'payment_method' => $data['payment_method'] ?? 'Tunai',
            'total' => $totalAmount,
            'cash_paid' => $cashPaid,
            'change' => $change,
            'items' => $itemsDetail,
        ]);
    }
}
