<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

$marker = dirname(__DIR__).'/storage/app/tropikal-deployment-20260816.done';
$keyHash = '1ecdcc573167dc7e07563579a46ceb59211b4837d49a53d7f1e04061d5c6d27b';
$completed = file_exists($marker);
$results = [];
$failed = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! $completed) {
    $key = (string) ($_POST['deployment_key'] ?? '');
    if (! hash_equals($keyHash, hash('sha256', $key))) {
        sleep(2);
        http_response_code(403);
        $error = 'Chave inválida.';
    } else {
        set_time_limit(180);
        require dirname(__DIR__).'/vendor/autoload.php';
        $app = require dirname(__DIR__).'/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        foreach ([['migrate', ['--force' => true]], ['db:seed', ['--force' => true]], ['optimize:clear', []]] as [$command, $arguments]) {
            $exitCode = Artisan::call($command, $arguments);
            $results[] = ['command' => $command, 'exit_code' => $exitCode, 'output' => trim(Artisan::output())];
            if ($exitCode !== 0) { $failed = true; http_response_code(500); break; }
        }
        if (! $failed) { file_put_contents($marker, date(DATE_ATOM)); $completed = true; }
    }
}
?><!doctype html><html lang="pt"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Atualizar Tropikal</title><style>body{margin:0;padding:24px;font-family:Arial,sans-serif;color:#123524;background:#eff4ed}.box{max-width:680px;margin:7vh auto;padding:32px;background:#fff;border-top:6px solid #58b947;box-shadow:0 20px 60px #1235241f}h1{margin-top:0;font-size:clamp(28px,8vw,48px)}p{line-height:1.6;color:#5c6e62}label{display:block;font-weight:700;margin:24px 0 8px}input{box-sizing:border-box;width:100%;padding:15px;border:2px solid #d9e6dc;font-size:16px}button{width:100%;margin-top:16px;padding:16px;border:0;background:#173f27;color:#fff;font-size:16px;font-weight:700}.ok{padding:18px;background:#e3f4db;color:#174727}.error{padding:18px;background:#fff0ed;color:#8d2820}pre{overflow:auto;padding:14px;background:#0b2015;color:#d9efce;font-size:12px;white-space:pre-wrap}</style></head><body><main class="box"><h1>Atualização Tropikal</h1><?php if ($completed && empty($results)): ?><div class="ok"><strong>Atualização já concluída.</strong><p>Este instalador está bloqueado.</p></div><?php elseif ($results): ?><div class="<?= $failed ? 'error' : 'ok' ?>"><strong><?= $failed ? 'A atualização encontrou um erro.' : 'Atualização concluída com sucesso.' ?></strong></div><?php foreach ($results as $result): ?><h2><?= htmlspecialchars($result['command']) ?></h2><pre>Código: <?= (int) $result['exit_code']."\n".htmlspecialchars($result['output'] ?: 'Concluído.') ?></pre><?php endforeach; ?><?php else: ?><p>Aplica as migrações, carrega o catálogo e limpa as caches. Só pode ser executado uma vez.</p><?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?><form method="post"><label for="deployment_key">Chave de atualização</label><input id="deployment_key" type="password" name="deployment_key" required autocomplete="off"><button type="submit">Atualizar Tropikal</button></form><?php endif; ?></main></body></html>
