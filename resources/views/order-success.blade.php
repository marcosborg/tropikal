@extends('layouts.site')
@section('title','Pedido recebido | TROPIKAL')
@section('content')<section class="section success-page"><div class="container empty-state"><span class="eyebrow">Pedido recebido</span><h1>Obrigado, {{ $order->name }}.</h1><p>A referência do seu pedido é <strong>{{ $order->reference }}</strong>. A nossa equipa irá confirmar preço, disponibilidade e condições.</p><a class="btn btn-primary" href="{{ route('catalog') }}">Voltar ao catálogo</a></div></section>@endsection
