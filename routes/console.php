<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('storage:verify-public {--repair : Criar a ligação apenas quando não existe}', function () {
    $public = public_path('storage');
    $target = storage_path('app/public');
    if (file_exists($public)) {
        $valid = realpath($public) === realpath($target);
        $valid ? $this->info('A ligação public/storage está correta.') : $this->error('public/storage existe, mas não aponta para storage/app/public. Nada foi alterado.');
        return $valid ? self::SUCCESS : self::FAILURE;
    }
    if (! $this->option('repair')) {
        $this->warn('A ligação public/storage não existe. Execute novamente com --repair para a criar sem remover ficheiros.');
        return self::FAILURE;
    }
    app('files')->link($target, $public);
    $this->info('A ligação public/storage foi criada.');
    return self::SUCCESS;
})->purpose('Verifica a ligação pública de uploads sem apagar ou substituir ficheiros');
