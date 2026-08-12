@extends('layouts.site')
@section('title',$category->name.' | TROPIKAL')
@section('description',$category->meta_description ?? $category->description)
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Catálogo</span><h1>{{ $category->name }}</h1><p>{{ $category->description }}</p>@if($category->children->isNotEmpty())<div class="chips">@foreach($category->children as $child)<a href="{{ route('category',$child) }}">{{ $child->name }}</a>@endforeach</div>@endif</div></section>
<section class="section"><div class="container"><div class="product-grid">@forelse($products as $product)@include('partials.product-card')@empty<p>A adicionar produtos. Contacte-nos para conhecer as soluções disponíveis.</p>@endforelse</div>{{ $products->links() }}</div></section>
<section class="section section-contact quote-band service-quote"><div class="container contact-grid"><div class="contact-info"><span class="eyebrow">{{ $category->name }}</span><h2>Precisa desta solução?</h2><p>Explique-nos o seu projeto e a equipa Tropikal apresenta-lhe a solução mais adequada.</p><div class="contact-promise"><strong>Apoio do início ao fim</strong><span>Equipamento, aconselhamento e instalação especializada.</span></div></div>@include('partials.quote-form',['quoteSubject' => 'Informação: '.$category->name])</div></section>
@endsection
