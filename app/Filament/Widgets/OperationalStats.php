<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\{Categories\CategoryResource,Products\ProductResource,QuoteRequests\QuoteRequestResource,Users\UserResource};
use App\Models\{Category,Product,QuoteRequest,User};

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationalStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Pedidos', QuoteRequest::count())->description('Todos os pedidos de orçamento')->descriptionIcon('heroicon-m-clipboard-document-list')->color('primary')->url(QuoteRequestResource::getUrl()),
            Stat::make('Novos pedidos', QuoteRequest::where('status','new')->count())->description('Aguardam contacto')->descriptionIcon('heroicon-m-bell-alert')->color('warning')->url(QuoteRequestResource::getUrl()),
            Stat::make('Produtos publicados', Product::where('is_published',true)->count())->description('Visíveis no catálogo')->descriptionIcon('heroicon-m-cube')->color('success')->url(ProductResource::getUrl()),
            Stat::make('Categorias', Category::count())->description('Estrutura do catálogo')->descriptionIcon('heroicon-m-tag')->url(CategoryResource::getUrl()),
            Stat::make('Utilizadores', User::count())->description('Acesso administrativo')->descriptionIcon('heroicon-m-users')->url(UserResource::getUrl()),
        ];
    }
}
