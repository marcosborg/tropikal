<?php
namespace Tests\Feature;
use App\Models\{Category,Product,QuoteRequest}; use Illuminate\Foundation\Testing\RefreshDatabase; use Tests\TestCase;
class CatalogTest extends TestCase { use RefreshDatabase;
 public function test_catalog_and_product_are_public():void { $c=Category::create(['name'=>'Teste','slug'=>'teste']); $p=Product::create(['category_id'=>$c->id,'name'=>'Produto','slug'=>'produto','is_published'=>true]); $this->get('/catalogo')->assertOk()->assertSee('Teste'); $this->get('/produtos/produto')->assertOk()->assertSee('Produto'); }
 public function test_quote_is_validated_and_stored():void { $this->post('/pedir-orcamento',['name'=>'Cliente','email'=>'cliente@example.com','message'=>'Quero receber um orçamento.'])->assertSessionHas('success'); $this->assertDatabaseHas('quote_requests',['email'=>'cliente@example.com']); }
 public function test_unpublished_product_is_not_public():void { $c=Category::create(['name'=>'Teste','slug'=>'teste']); Product::create(['category_id'=>$c->id,'name'=>'Oculto','slug'=>'oculto','is_published'=>false]); $this->get('/produtos/oculto')->assertNotFound(); }
}
