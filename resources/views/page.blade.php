@extends('layouts.site')
@section('title',($page->meta_title ?: $page->title).' | TROPIKAL')
@section('description',$page->meta_description ?? '')
@section('content')
@php($hero = $page->hero_image ? (str_starts_with($page->hero_image,'assets/') ? asset($page->hero_image) : asset('storage/'.$page->hero_image)) : null)
<section class="page-hero compact page-editorial-hero{{ $hero ? ' has-image' : '' }}" @if($hero) style="--page-hero-image:url('{{ $hero }}')" @endif><div class="container"><span class="eyebrow eyebrow-dark">Tropikal</span><h1>{{ $page->title }}</h1></div></section>
<section class="section"><article class="container prose">{!! $page->content !!}</article></section>
@endsection
