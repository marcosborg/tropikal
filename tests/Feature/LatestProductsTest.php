<?php

namespace Tests\Feature;

use App\Models\{Category, Product};
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LatestProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_latest_tropikal_families_and_variants_are_seeded_idempotently(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $ac = Product::where('slug', 'ar-condicionado-tropikal-tk')->firstOrFail();
        $this->assertSame(3, $ac->variants()->count());
        $this->assertDatabaseHas('product_variants', ['sku' => 'TK-12', 'price' => 275]);
        $this->assertDatabaseHas('product_variants', ['sku' => 'TK-18', 'price' => 390]);
        $this->assertDatabaseHas('product_variants', ['sku' => 'TK-24', 'price' => 550]);

        $xpro = Product::where('slug', 'tropikal-xpro-square-r290')->firstOrFail();
        $round = Product::where('slug', 'tropikal-round-r290')->firstOrFail();
        $this->assertSame(3, $xpro->variants()->count());
        $this->assertSame(5, $round->variants()->count());
        $this->assertSame('278 L', $xpro->variants()->where('sku', 'XPRO300')->firstOrFail()->options['capacidade técnica']);
    }

    public function test_home_only_shows_active_promotions_with_approved_images(): void
    {
        $category = Category::create(['name' => 'Teste', 'slug' => 'teste']);
        $active = Product::create(['category_id' => $category->id, 'name' => 'Promo ativa', 'slug' => 'promo-ativa', 'is_published' => true, 'is_promotion' => true, 'price' => 10]);
        $active->images()->create(['path' => 'assets/img/products/painel-720w-tropikal.svg', 'is_feature_approved' => true]);
        $expired = Product::create(['category_id' => $category->id, 'name' => 'Promo expirada', 'slug' => 'promo-expirada', 'is_published' => true, 'is_promotion' => true, 'promotion_ends_at' => now()->subMinute(), 'price' => 10]);
        $expired->images()->create(['path' => 'assets/img/products/painel-720w-tropikal.svg', 'is_feature_approved' => true]);

        $this->get('/')->assertOk()->assertSee('Promo ativa')->assertDontSee('Promo expirada');
    }

    public function test_catalog_searches_variant_name_and_sku(): void
    {
        $category = Category::create(['name' => 'Climatização', 'slug' => 'climatizacao']);
        $product = Product::create(['category_id' => $category->id, 'name' => 'Tropikal TK', 'slug' => 'tk', 'is_published' => true]);
        $product->variants()->create(['name' => '12.000 BTU', 'sku' => 'TK-12']);

        $this->get('/catalogo?q=TK-12')->assertOk()->assertSee('Tropikal TK');
        $this->get('/catalogo?q=12.000')->assertOk()->assertSee('Tropikal TK');
    }
}
