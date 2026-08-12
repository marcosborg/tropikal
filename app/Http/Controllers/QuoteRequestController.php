<?php

namespace App\Http\Controllers;

use App\Mail\QuoteRequestReceived; use App\Models\{QuoteRequest,SiteSetting}; use Illuminate\Http\Request; use Illuminate\Support\Facades\Mail;

class QuoteRequestController extends Controller
{
    public function store(Request $request){
        $data=$request->validate(['product_id'=>'nullable|exists:products,id','name'=>'required|string|max:120','email'=>'required|email|max:160','phone'=>'nullable|string|max:40','subject'=>'nullable|string|max:160','message'=>'required|string|max:5000','website'=>'nullable|max:0']); unset($data['website']);
        $quote=QuoteRequest::create($data);
        try { $to=SiteSetting::where('key','email')->value('value') ?: config('mail.from.address'); Mail::to($to)->send(new QuoteRequestReceived($quote->load('product'))); $quote->update(['notified_at'=>now()]); } catch (\Throwable $e) { report($e); }
        return back()->with('success','Pedido enviado com sucesso. Entraremos em contacto brevemente.');
    }
}
