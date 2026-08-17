<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    private function data(): array
    {
        return ['settings' => SiteSetting::pluck('value', 'key'), 'navCategories' => Category::whereNull('parent_id')->where('is_published', true)->orderBy('sort_order')->get()];
    }

    public function home()
    {
        $base = fn () => Product::with(['images' => fn ($q) => $q->where('is_feature_approved', true), 'category', 'variants'])->where('is_published', true)->whereHas('images', fn ($q) => $q->where('is_feature_approved', true));

        return view('home', $this->data() + ['featured' => $base()->where('is_featured', true)->orderBy('sort_order')->take(4)->get(), 'promotions' => $base()->activePromotion()->orderBy('sort_order')->take(8)->get()]);
    }

    public function catalog(Request $request)
    {
        $term = trim((string) $request->string('q'));
        $products = Product::with('images', 'category', 'variants')->where('is_published', true)->when($term, fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', '%'.$term.'%')->orWhere('reference', 'like', '%'.$term.'%')->orWhere('excerpt', 'like', '%'.$term.'%')->orWhereHas('variants', fn ($v) => $v->where('name', 'like', '%'.$term.'%')->orWhere('sku', 'like', '%'.$term.'%'))))->orderBy('sort_order')->orderBy('name')->paginate(12)->withQueryString();

        return view('catalog', $this->data() + ['categories' => Category::with(['children'])->whereNull('parent_id')->where('is_published', true)->orderBy('sort_order')->get(), 'products' => $products]);
    }

    public function category(Category $category)
    {
        abort_unless($category->is_published, 404);

        return view('category', $this->data() + ['category' => $category->load('children'), 'products' => Product::with('images', 'variants')->whereIn('category_id', collect([$category->id])->merge($category->children->pluck('id')))->where('is_published', true)->paginate(12)]);
    }

    public function product(Product $product)
    {
        abort_unless($product->is_published, 404);

        return view('product', $this->data() + ['product' => $product->load('category', 'images', 'documents', 'variants')]);
    }

    public function page(string $slug)
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('page', $this->data() + compact('page'));
    }
}
