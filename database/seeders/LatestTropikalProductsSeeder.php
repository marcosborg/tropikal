<?php

namespace Database\Seeders;

use App\Models\{Category, Product};
use Illuminate\Database\Seeder;

class LatestTropikalProductsSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->update(['is_featured' => false]);

        $category = fn (string $slug) => Category::where('slug', $slug)->firstOrFail()->id;
        $save = function (string $slug, array $data, array $variants = [], array $images = [], array $documents = []) use ($category): Product {
            $data['category_id'] = $category($data['category']);
            unset($data['category']);
            $product = Product::updateOrCreate(['slug' => $slug], $data + ['is_published' => true, 'is_purchasable' => false]);
            foreach ($variants as $index => $variant) {
                $product->variants()->updateOrCreate(['sku' => $variant['sku']], $variant + ['sort_order' => $index]);
            }
            foreach ($images as $index => $image) {
                $product->images()->updateOrCreate(['path' => $image['path']], $image + ['sort_order' => $index]);
            }
            foreach ($documents as $index => $document) {
                $product->documents()->updateOrCreate(['path' => $document['path']], $document + ['sort_order' => $index]);
            }
            return $product;
        };

        $ac = Product::where('slug', 'ar-condicionado-eco')->first();
        if ($ac) Product::where('slug', 'ar-condicionado-tropikal-tk')->exists() ? $ac->update(['is_published'=>false,'is_featured'=>false]) : $ac->update(['slug' => 'ar-condicionado-tropikal-tk']);
        $save('ar-condicionado-tropikal-tk', [
            'category' => 'ar-condicionado', 'name' => 'Ar Condicionado Tropikal TK', 'reference' => 'TK-12/18/24 INV',
            'excerpt' => 'Climatização Tropikal inverter, com Wi‑Fi, aquecimento e arrefecimento.',
            'description' => 'Família Tropikal TK preparada para conforto ao longo de todo o ano. Preço, stock, transporte e instalação são confirmados pela Tropikal antes da encomenda.',
            'features' => [['label'=>'Tecnologia','value'=>'DC Inverter'],['label'=>'Refrigerante','value'=>'R32'],['label'=>'Alimentação','value'=>'220–240 V / 50 Hz'],['label'=>'Controlo','value'=>'Wi‑Fi']],
            'is_featured' => true, 'is_promotion' => true, 'sort_order' => 10,
        ], [
            ['name'=>'TK-12 · 12.000 BTU','sku'=>'TK-12','price'=>275,'options'=>['unidade interior'=>'TK-12INVINT','unidade exterior'=>'TK-12INVEXT','capacidade'=>'12.000 BTU','refrigerante'=>'R32','alimentação'=>'220–240 V / 50 Hz']],
            ['name'=>'TK-18 · 18.000 BTU','sku'=>'TK-18','price'=>390,'options'=>['unidade interior'=>'TK-18INVINT','unidade exterior'=>'TK-18INVEXT','capacidade'=>'18.000 BTU','refrigerante'=>'R32','alimentação'=>'220–240 V / 50 Hz']],
            ['name'=>'TK-24 · 24.000 BTU','sku'=>'TK-24','price'=>550,'options'=>['unidade interior'=>'TK-24INVINT','unidade exterior'=>'TK-24INVEXT','capacidade'=>'24.000 BTU','refrigerante'=>'R32','alimentação'=>'220–240 V / 50 Hz']],
        ], [['path'=>'assets/img/products/ar-condicionado-eco.jpg','alt_text'=>'Ar condicionado Tropikal TK','is_feature_approved'=>true]], [['path'=>'assets/manuals/Tropikal-TK-ar-condicionado.pdf','title'=>'Ficha técnica Tropikal TK']]);

        $legacyHeatPump = Product::where('slug','xpro300')->first();
        if ($legacyHeatPump) Product::where('slug','tropikal-xpro-square-r290')->exists() ? $legacyHeatPump->update(['is_published'=>false,'is_featured'=>false]) : $legacyHeatPump->update(['slug'=>'tropikal-xpro-square-r290']);
        $save('tropikal-xpro-square-r290', [
            'category'=>'bombas-de-calor','name'=>'Bomba de Calor Tropikal XPRO Square R290','reference'=>'XPRO200/250/300',
            'excerpt'=>'Bomba de calor para água quente, com depósito integrado e controlo Wi‑Fi.',
            'description'=>'Gama XPRO Square Tropikal com refrigerante R290. As designações comerciais 200, 250 e 300 correspondem a capacidades técnicas de depósito de 200, 244 e 278 litros.',
            'features'=>[['label'=>'Refrigerante','value'=>'R290'],['label'=>'Temperatura máxima','value'=>'Até 75 °C'],['label'=>'Controlo','value'=>'Wi‑Fi / IoT'],['label'=>'Alimentação','value'=>'220–240 V / 50 Hz']],
            'is_featured'=>true,'is_promotion'=>true,'sort_order'=>20,
        ], [
            ['name'=>'XPRO200 · depósito 200 L','sku'=>'XPRO200','options'=>['capacidade técnica'=>'200 L','classe ERP (ar 20 °C)'=>'A++','COP nominal'=>'3,92']],
            ['name'=>'XPRO250 · depósito 244 L','sku'=>'XPRO250','options'=>['capacidade técnica'=>'244 L','classe ERP (ar 20 °C)'=>'A++','COP nominal'=>'3,92']],
            ['name'=>'XPRO300 · depósito 278 L','sku'=>'XPRO300','price'=>1390,'options'=>['capacidade técnica'=>'278 L','classe ERP (ar 20 °C)'=>'A++','COP nominal'=>'3,92']],
        ], [
            ['path'=>'assets/img/products/tropikal-xpro-square.svg','alt_text'=>'Bomba de calor Tropikal XPRO Square','is_feature_approved'=>true],
            ['path'=>'assets/img/products/bomba-xpro300.png','alt_text'=>'Bomba de calor Tropikal XPRO Square','is_feature_approved'=>false],
            ['path'=>'assets/img/products/tropikal-xpro-fabrica-01.webp','alt_text'=>'Produção de bombas de calor Tropikal XPRO','is_feature_approved'=>false],
            ['path'=>'assets/img/products/tropikal-xpro-fabrica-02.webp','alt_text'=>'Equipamentos Tropikal XPRO em produção','is_feature_approved'=>false],
            ['path'=>'assets/img/products/tropikal-xpro-fabrica-03.webp','alt_text'=>'Detalhe da produção Tropikal XPRO','is_feature_approved'=>false],
        ], [['path'=>'assets/manuals/Tropikal-XPRO-Square-R290.pdf','title'=>'Ficha técnica XPRO Square R290']]);

        $save('tropikal-round-r290', [
            'category'=>'bombas-de-calor','name'=>'Bomba de Calor Tropikal Round R290','reference'=>'x120/x150/x200/x250/x300',
            'excerpt'=>'Água quente eficiente em formato cilíndrico, de 120 a 300 litros.',
            'description'=>'Família Round R290 para aplicações domésticas e comerciais ligeiras. As classes e o desempenho variam conforme capacidade e condições de ensaio indicadas na ficha técnica.',
            'features'=>[['label'=>'Capacidades','value'=>'120–300 L'],['label'=>'Refrigerante','value'=>'R290'],['label'=>'Temperatura máxima','value'=>'Até 75 °C'],['label'=>'Alimentação','value'=>'220–240 V / 50 Hz']],
            'sort_order'=>21,
        ], collect([120,150,200,250,300])->map(fn($litres)=>['name'=>"x{$litres} · {$litres} L",'sku'=>"ROUND-X{$litres}",'options'=>['capacidade'=>"{$litres} L",'formato'=>'Cilíndrico','disponibilidade'=>'Sob confirmação']])->all(), [['path'=>'assets/img/products/tropikal-round-r290.svg','alt_text'=>'Bomba de calor Tropikal Round R290','is_feature_approved'=>false]], [['path'=>'assets/manuals/Tropikal-Round-R290.pdf','title'=>'Ficha técnica Tropikal Round R290']]);

        $solar = Product::where('slug','solarspace-700wp')->first();
        $solar?->update(['is_featured'=>true,'sort_order'=>30]);
        $solar?->images()->update(['is_feature_approved'=>true]);
        $deye6 = Product::where('slug','deye-sun-6-8k')->first();
        $deye6?->update(['is_promotion'=>true]);
        $deye6?->variants()->where('name','6 kW')->update(['price'=>890]);
        $deye6?->images()->updateOrCreate(['path'=>'assets/img/products/inversor-deye-tropikal.svg'],['alt_text'=>'Inversor híbrido Deye SUN SG05','is_feature_approved'=>true,'sort_order'=>0]);
        $deye6?->documents()->updateOrCreate(['path'=>'assets/manuals/Deye-SUN-SG05-3.6-10kW.pdf'],['title'=>'Ficha técnica Deye SUN SG05']);
        $deye12 = Product::where('slug','deye-sun-10-12k')->first();
        $deye12?->update(['is_promotion'=>true]);
        $deye12?->variants()->updateOrCreate(['sku'=>'SUN-12K-SG02LP1-EU-AM3'],['name'=>'12 kW','price'=>1890,'options'=>['disponibilidade'=>'Sob confirmação']]);
        $deye12?->images()->updateOrCreate(['path'=>'assets/img/products/inversor-deye-tropikal.svg'],['alt_text'=>'Inversor híbrido Deye 12 kW','is_feature_approved'=>true,'sort_order'=>0]);

        $save('painel-solar-720w', ['category'=>'paineis-solares','name'=>'Painel Solar 720 W','excerpt'=>'Painel fotovoltaico de alta potência, com referência técnica a confirmar.','description'=>'Família comercial publicada a pedido da Tropikal. Fabricante, referência, características, stock e preço final serão confirmados antes da encomenda.','is_promotion'=>true,'sort_order'=>31], [['name'=>'720 W','sku'=>'SOLAR-720-PENDING','price'=>189,'options'=>['potência'=>'720 W','referência técnica'=>'Pendente de confirmação']]], [['path'=>'assets/img/products/painel-720w-tropikal.svg','alt_text'=>'Painel solar 720 W','is_feature_approved'=>true]]);
        $save('bateria-deye-16kwh', ['category'=>'baterias','name'=>'Bateria Deye 16 kWh','excerpt'=>'Armazenamento de energia Deye, desde 1.680 €.','description'=>'Família comercial com modelo e configuração sujeitos a confirmação. Não são apresentadas garantias sem documento específico associado.','price'=>1680,'is_promotion'=>true,'sort_order'=>32], [['name'=>'16 kWh · configuração sob confirmação','sku'=>'DEYE-16KWH-PENDING','price'=>1680,'options'=>['capacidade anunciada'=>'16 kWh','modelo'=>'Pendente de confirmação']]], [['path'=>'assets/img/products/bateria-deye-16kwh.svg','alt_text'=>'Bateria Deye 16 kWh','is_feature_approved'=>true]]);
        $save('disjuntores-inteligentes-zigbee', ['category'=>'domotica','name'=>'Disjuntores Inteligentes Zigbee','excerpt'=>'Proteção e controlo inteligente de circuitos, desde 17 €.','description'=>'Família Zigbee publicada com referência, calibres e compatibilidade sujeitos a confirmação.','price'=>17,'is_promotion'=>true,'sort_order'=>41], [['name'=>'Configuração sob confirmação','sku'=>'ZIGBEE-BREAKER-PENDING','price'=>17,'options'=>['protocolo'=>'Zigbee','calibre'=>'Sob confirmação']]], [['path'=>'assets/img/products/disjuntor-zigbee.svg','alt_text'=>'Disjuntor inteligente Zigbee','is_feature_approved'=>true]]);

        $switch = Product::where('slug','interruptores-y-series')->first();
        $switch?->update(['is_featured'=>true,'is_promotion'=>true,'price'=>15,'sort_order'=>40]);
        $switch?->images()->update(['is_feature_approved'=>true]);
    }
}
