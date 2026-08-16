<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase; use Tests\TestCase;
class DeploymentTest extends TestCase { use RefreshDatabase;
 public function test_deployment_form_is_available_and_rejects_invalid_key():void { $this->get('/manutencao/atualizar-tropikal-2026')->assertOk()->assertSee('Atualização Tropikal'); $this->post('/manutencao/atualizar-tropikal-2026',['deployment_key'=>'errada'])->assertForbidden(); }
}
