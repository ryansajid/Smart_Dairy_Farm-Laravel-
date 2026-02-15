<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class FrontendController extends Controller
{
    /**
     * Display the home page with categories and products.
     */
    public function home()
    {
        $categories = Category::active()->ordered()->get();
        $featuredProducts = Product::active()->featured()->ordered()->limit(8)->get();
        $latestProducts = Product::active()->ordered()->latest()->limit(8)->get();
        
        return view('home', compact('categories', 'featuredProducts', 'latestProducts'));
    }

    /**
     * Display the shop page with all products.
     */
    public function shop(Request $request)
    {
        $categories = Category::active()->ordered()->get();
        
        $products = Product::active()
            ->with('category')
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->ordered()
            ->paginate(12);

        return view('shop', compact('categories', 'products'));
    }
}