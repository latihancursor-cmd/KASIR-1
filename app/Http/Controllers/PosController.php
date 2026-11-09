<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $todayRevenue     = Sale::whereDate('created_at', today())->sum('total_price');
        $todayCount       = Sale::whereDate('created_at', today())->count();
        $itemsSold        = Sale::whereDate('created_at', today())->sum('quantity');
        $lastTransactions = Sale::latest()->take(5)->get();
        $products         = Product::with('category')->get();

        return view('kasir.index', compact('todayRevenue','todayCount','itemsSold','lastTransactions','products'));
    }

    public function purr()
    {
        $categories = Category::all();
        $products = Product::with('category')->get();
        
        return view('kasir.purr', compact('categories', 'products'));
    }

    public function beranda()
    {
        $todayRevenue = Sale::whereDate('created_at', today())->sum('total_price');
        $todayCount = Sale::whereDate('created_at', today())->count();
        $itemsSold = Sale::whereDate('created_at', today())->sum('quantity');
        $lastTransactions = Sale::with('product')->whereDate('created_at', today())->latest()->take(8)->get();
        $topProducts = Sale::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_revenue'))
            ->whereDate('created_at', today())
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('kasir.beranda', compact('todayRevenue', 'todayCount', 'itemsSold', 'lastTransactions', 'topProducts'));
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            $total = 0;
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = $item['quantity'];
                if ($product->stock < $qty) {
                    abort(400, "Stok tidak cukup untuk {$product->name}");
                }
                $product->decrement('stock', $qty);
                $price = $product->price * $qty;
                $total += $price;
                Sale::create([
                    'user_id'     => Auth::id(),
                    'product_id'  => $product->id,
                    'quantity'   => $qty,
                    'total_price' => $price,
                ]);
            }
        });

        return redirect()->route('kasir.index')->with('success', 'Transaksi selesai');
    }
}
