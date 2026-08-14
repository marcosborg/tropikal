@extends('layouts.site')
@section('title','Catálogo | TROPIKAL')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Catálogo</span><h1>Produtos e soluções</h1><p>Equipamentos para energia, climatização, domótica e construção.</p></div></section>
<section class="section"><div class="container"><div class="section-heading"><span class="eyebrow">Áreas de atuação</span><h2>Explore por categoria</h2></div><div class="category-grid">@foreach($categories as $category)<a class="category-card" href="{{ route('category',$category) }}"><h2>{{ $category->name }}</h2><p>{{ $category->description }}</p><span>{{ $category->children->count() }} subcategorias</span></a>@endforeach</div></div></section>
<section class="section section-alt"><div class="container"><div class="section-heading center"><span class="eyebrow">Produtos reais</span><h2>Catálogo Tropikal</h2><p class="section-intro">Equipamentos disponíveis para orçamento, instalação e revenda.</p></div><div class="product-grid">@forelse($products as $product)@include('partials.product-card')@empty<p>A adicionar produtos. Contacte-nos para conhecer as soluções disponíveis.</p>@endforelse</div>{{ $products->links() }}</div></section>
@endsection
