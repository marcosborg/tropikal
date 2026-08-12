<?php

namespace App\Filament\Widgets;

use App\Models\QuoteRequest;
use Illuminate\Support\Carbon;

use Filament\Widgets\ChartWidget;

class QuoteTrendChart extends ChartWidget
{
    protected ?string $heading = 'Evolução dos pedidos';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $months=collect(range(11,0))->map(fn($offset)=>now()->startOfMonth()->subMonths($offset));
        return ['datasets'=>[['label'=>'Pedidos','data'=>$months->map(fn(Carbon $month)=>QuoteRequest::whereBetween('created_at',[$month->copy()->startOfMonth(),$month->copy()->endOfMonth()])->count())->all(),'borderColor'=>'#22c55e','backgroundColor'=>'rgba(34,197,94,.15)','fill'=>true]],'labels'=>$months->map(fn(Carbon $month)=>$month->translatedFormat('M Y'))->all()];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
