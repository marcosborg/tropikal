<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeploymentController extends Controller
{
    private const KEY_HASH = '1ecdcc573167dc7e07563579a46ceb59211b4837d49a53d7f1e04061d5c6d27b';

    private function marker(): string
    {
        return storage_path('app/tropikal-deployment-20260816.done');
    }

    public function form()
    {
        return view('deployment', ['completed' => file_exists($this->marker())]);
    }

    public function run(Request $request)
    {
        abort_if(file_exists($this->marker()), 410, 'Esta atualização já foi executada.');
        $request->validate(['deployment_key' => 'required|string|max:100']);
        abort_unless(hash_equals(self::KEY_HASH, hash('sha256', $request->string('deployment_key')->toString())), 403, 'Chave inválida.');

        set_time_limit(180);
        $results = [];
        foreach ([['migrate', ['--force' => true]], ['db:seed', ['--force' => true]], ['optimize:clear', []]] as [$command, $arguments]) {
            $exitCode = Artisan::call($command, $arguments);
            $results[] = ['command' => $command, 'exit_code' => $exitCode, 'output' => trim(Artisan::output())];
            if ($exitCode !== 0) return response()->view('deployment', ['completed' => false, 'results' => $results, 'failed' => true], 500);
        }

        file_put_contents($this->marker(), now()->toIso8601String());
        return view('deployment', ['completed' => true, 'results' => $results, 'failed' => false]);
    }
}
