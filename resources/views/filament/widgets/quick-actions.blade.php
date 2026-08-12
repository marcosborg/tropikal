<x-filament-widgets::widget>
    <x-filament::section heading="Ações rápidas" description="Aceda diretamente às tarefas mais frequentes.">
        <div class="flex flex-wrap gap-3">
            <x-filament::button tag="a" :href="\App\Filament\Resources\Products\ProductResource::getUrl('create')" icon="heroicon-m-plus">Novo produto</x-filament::button>
            <x-filament::button tag="a" :href="\App\Filament\Resources\Categories\CategoryResource::getUrl('create')" icon="heroicon-m-tag" color="gray">Nova categoria</x-filament::button>
            <x-filament::button tag="a" :href="\App\Filament\Resources\QuoteRequests\QuoteRequestResource::getUrl()" icon="heroicon-m-clipboard-document-list" color="gray">Ver pedidos</x-filament::button>
            <x-filament::button tag="a" :href="\App\Filament\Resources\Users\UserResource::getUrl()" icon="heroicon-m-users" color="gray">Gerir utilizadores</x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
