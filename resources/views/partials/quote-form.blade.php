<form class="contact-form quote-form" method="post" action="{{ route('quote.store') }}">
    @csrf
    @if(isset($product))<input type="hidden" name="product_id" value="{{ $product->id }}">@endif
    <div class="honeypot"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
    <div class="form-heading"><span>Pedido rápido</span><strong>Fale com a nossa equipa</strong></div>
    @if(session('success'))<p class="form-feedback is-success">{{ session('success') }}</p>@endif
    @if($errors->any())<p class="form-feedback is-error">Verifique os campos do formulário.</p>@endif
    <div class="form-row"><label>Nome<input name="name" value="{{ old('name') }}" placeholder="O seu nome" required></label><label>Email<input type="email" name="email" value="{{ old('email') }}" placeholder="nome@exemplo.pt" required></label></div>
    <div class="form-row"><label>Telefone<input name="phone" value="{{ old('phone') }}" placeholder="+351 9XX XXX XXX"></label><label>Assunto<input name="subject" value="{{ old('subject', isset($product) ? 'Orçamento: '.$product->name : ($quoteSubject ?? 'Pedido de orçamento')) }}"></label></div>
    <label>Mensagem<textarea name="message" rows="5" placeholder="Conte-nos brevemente o que procura" required>{{ old('message') }}</textarea></label>
    <button class="btn btn-primary form-submit" type="submit"><span>Enviar pedido</span><span aria-hidden="true">→</span></button>
</form>
