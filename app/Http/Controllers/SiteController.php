<?php

namespace App\Http\Controllers;

use App\Models\{Category,Page,Product,SiteSetting};

use Illuminate\Http\Request;

class SiteController extends Controller
{
    private function data(): array { return ['settings'=>SiteSetting::pluck('value','key'), 'navCategories'=>Category::whereNull('parent_id')->where('is_published',true)->orderBy('sort_order')->get()]; }
    public function home(){ return view('home', $this->data()+['featured'=>Product::with(['images'=>fn($q)=>$q->where('is_feature_approved',true),'category'])->where('is_featured',true)->where('is_published',true)->whereHas('images',fn($q)=>$q->where('is_feature_approved',true))->orderBy('sort_order')->take(4)->get()]); }
    public function catalog(Request $request){ $products=Product::with('images','category')->where('is_published',true)->when($request->filled('q'),fn($q)=>$q->where(fn($w)=>$w->where('name','like','%'.$request->string('q').'%')->orWhere('reference','like','%'.$request->string('q').'%')))->orderBy('sort_order')->orderBy('name')->paginate(12)->withQueryString(); return view('catalog', $this->data()+['categories'=>Category::with(['children'])->whereNull('parent_id')->where('is_published',true)->orderBy('sort_order')->get(), 'products'=>$products]); }
    public function category(Category $category){ abort_unless($category->is_published,404); return view('category', $this->data()+['category'=>$category->load('children'), 'products'=>Product::with('images')->whereIn('category_id', collect([$category->id])->merge($category->children->pluck('id')))->where('is_published',true)->paginate(12)]); }
    public function product(Product $product){ abort_unless($product->is_published,404); return view('product', $this->data()+['product'=>$product->load('category','images','documents','variants')]); }
    public function page(string $slug){ $page=Page::where('slug',$slug)->where('is_published',true)->firstOrFail(); return view('page', $this->data()+compact('page')); }
}
