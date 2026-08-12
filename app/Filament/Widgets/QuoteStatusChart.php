<?php

namespace App\Filament\Widgets;

use App\Models\QuoteRequest;

use Filament\Widgets\ChartWidget;

class QuoteStatusChart extends ChartWidget
{
    protected ?string $heading = 'Pedidos por estado';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        return ['datasets'=>[['data'=>[QuoteRequest::where('status','new')->count(),QuoteRequest::where('status','contacted')->count(),QuoteRequest::where('status','closed')->count()],'backgroundColor'=>['#f59e0b','#3b82f6','#22c55e']]],'labels'=>['Novos','Contactados','Fechados']];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
