<?php

namespace Database\Seeders;

use App\Models\{Category,Page,Product,SiteSetting,User}; use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::firstOrCreate(['email'=>'admin@tropikal.pt'],['name'=>'Administrador Tropikal','password'=>Hash::make(env('ADMIN_PASSWORD','ChangeMe-'.bin2hex(random_bytes(10))))]);
        $admin->update(['is_admin'=>true]);
        if (env('ADMIN_PASSWORD')) $admin->update(['name'=>'Administrador Tropikal','password'=>Hash::make(env('ADMIN_PASSWORD'))]);
        $cats=[]; foreach([['Climatização','climatizacao',null],['Ar Condicionado','ar-condicionado','climatizacao'],['Sistemas Solares','sistemas-solares',null],['Inversores','inversores','sistemas-solares'],['Painéis Solares','paineis-solares','sistemas-solares'],['Estruturas','estruturas','sistemas-solares'],['Baterias','baterias','sistemas-solares'],['Domótica','domotica',null],['Bombas de Calor','bombas-de-calor',null],['Construção','construcao',null],['Pladur','pladur','construcao'],['MDF','mdf','construcao'],['Capoto','capoto','construcao'],['Cozinhas','cozinhas','construcao'],['Vinílico','vinilico','construcao']] as [$name,$slug,$parent]) { $cats[$slug]=Category::updateOrCreate(['slug'=>$slug],['name'=>$name,'parent_id'=>$parent ? $cats[$parent]->id:null,'description'=>'Soluções profissionais Tropikal para particulares, empresas, instaladores e revendedores.','is_published'=>true]); }
        $products=[
          ['bombas-de-calor','Bomba de Calor Tropikal XPRO300','xpro300','XPRO300','Bomba de calor A++ para águas quentes sanitárias.','Equipamento Tropikal com refrigerante R290, controlo Wi-Fi, temperatura de água até 75 °C e proteção por ânodo de magnésio.','bomba-xpro300.png','00000402-Bomba calor Tropikal xpro300.pdf'],
          ['inversores','Inversor Híbrido Deye SUN 10/12K','deye-sun-10-12k','SUN-10/12K-SG02LP1-EU-AM3','Inversor híbrido monofásico para sistemas solares.','Ecrã tátil, proteção IP65, ligação AC a sistemas existentes e suporte de várias baterias em paralelo.','inversor-deye-10-12k.png','00000403-SUN-10-12K-SG02LP1-EU-AM3.pdf'],
          ['inversores','Inversor Híbrido Deye SUN 6/8K','deye-sun-6-8k','SUN-6K8K-SG05LP1-EU-AM2-P','Inversor híbrido monofásico para produção e armazenamento solar.','Solução Deye para autoconsumo, armazenamento e gestão inteligente de energia.','inversor-deye-6-8k.png','00000404-SUN-6K8K-SG05LP1-EU-AM2-P.pdf'],
          ['ar-condicionado','Ar Condicionado Série Eco','ar-condicionado-eco','MS-12/18/24HVCJ-ECO','Climatização Tropikal eficiente para habitação e comércio.','Série Eco disponível nas referências MS-12HVCG/ECO, MS-18HVCJ/ECO e MS-24HVCJ/ECO, com instalação por técnicos qualificados.','ar-condicionado-eco.jpg','Ficha-Tecnica-Ar-Condicionado-Eco.pdf'],
          ['paineis-solares','Painel Solarspace Lumina II 700 Wp','solarspace-700wp','SS9-66HD 695-715N','Módulo bifacial N-TOPCon de alta potência.','Painel Solarspace Lumina II com potência máxima até 715 W, eficiência até 23,02% e garantia linear de 30 anos.','painel-solarspace-700wp.png','00000407-Solarspace-700wp data.pdf'],
        ]; foreach($products as [$cat,$name,$slug,$ref,$excerpt,$desc,$image,$doc]) { $p=Product::updateOrCreate(['slug'=>$slug],['category_id'=>$cats[$cat]->id,'name'=>$name,'reference'=>$ref,'excerpt'=>$excerpt,'description'=>$desc,'is_featured'=>true,'is_published'=>true]); $p->images()->updateOrCreate(['path'=>'assets/img/products/'.$image],['alt_text'=>$name]); $p->documents()->updateOrCreate(['path'=>'assets/manuals/'.$doc],['title'=>'Ficha técnica / manual']); }
        $smart=[['Painéis de Controlo Smart Home','paineis-controlo-smart-home','smart-home-paineis.jpg',false],['Interruptores Inteligentes Y Series','interruptores-y-series','interruptores-y-series.jpg',true],['Interruptores Inteligentes D Series','interruptores-d-series','interruptores-d-series.jpg',false],['Termóstato Touch Inteligente','termostato-touch-inteligente','termostato-touch.jpg',true],['Gateway Smart Home','gateway-smart-home','gateway-smart.jpg',true],['Sistema Inteligente de Cortinas','sistema-inteligente-cortinas','cortinas-smart.jpg',true]]; foreach($smart as [$name,$slug,$image,$featured]) { $p=Product::updateOrCreate(['slug'=>$slug],['category_id'=>$cats['domotica']->id,'name'=>$name,'excerpt'=>'Solução de domótica Tropikal para controlo e automação do espaço.','description'=>'Produto apresentado no Catálogo Smart Home Tropikal 2026. Consulte o catálogo ou peça informação para conhecer modelos, acabamentos e compatibilidades disponíveis.','is_featured'=>$featured,'is_published'=>true]); $p->images()->updateOrCreate(['path'=>'assets/img/products/'.$image],['alt_text'=>$name,'is_feature_approved'=>$featured]); $p->documents()->updateOrCreate(['path'=>'assets/manuals/00000405-Catalogo_Tropikal_Acores_Completo.pdf'],['title'=>'Catálogo Smart Home Tropikal 2026']); }

        $families = [
            ['Interruptores D89 Metal','interruptores-d89-metal','D89','Interruptores táteis Zigbee/Tuya com acabamento metálico.',['1 tecla','2 teclas','3 teclas','4 teclas']],
            ['Interruptores D8C','interruptores-d8c','D8C','Interruptores táteis Zigbee/Tuya em acabamento plástico.',['1 tecla','2 teclas','3 teclas','4 teclas']],
            ['Interruptores D29A Full Metal','interruptores-d29a-full-metal','D29A','Comandos táteis premium em metal para iluminação e cenários.',['1 tecla','2 teclas','3 teclas','4 teclas']],
            ['Interruptores Y8B Metal','interruptores-y8b-metal','Y8B','Comandos inteligentes de superfície metálica.',['1 tecla','2 teclas','3 teclas','4 teclas']],
            ['Interruptores T7 Vidro','interruptores-t7-vidro','T7','Interruptores em vidro temperado com indicação LED.',['1 tecla','2 teclas','3 teclas','4 teclas']],
            ['Interruptores D5 Vidro','interruptores-d5-vidro','D5','Série tátil em vidro temperado para controlo de iluminação.',['1 tecla','2 teclas','3 teclas','4 teclas']],
            ['Interruptores D8/ED8','interruptores-d8-ed8','D8/ED8','Série tátil para combinações e cenários personalizados.',['1 tecla','2 teclas','3 teclas','4 teclas','5 teclas']],
            ['Tomadas e Módulos Inteligentes','tomadas-modulos-inteligentes','EU/UK','Tomadas, módulos de relé e acessórios para integração inteligente.',['Tomada EU','Tomada UK','Módulo relé','Módulo cortina']],
            ['Dimmers Inteligentes','dimmers-inteligentes','DIM','Controlo inteligente de intensidade, temperatura de cor e cenários.',['Dimmer tátil','Dimmer rotativo','Dimmer luz e cor']],
            ['Termóstato Rotativo Inteligente','termostato-rotativo-inteligente','KNOB','Termóstato Zigbee/Tuya com comando rotativo e indicação digital.',['Aquecimento','Climatização','Ventilação']],
        ];
        foreach ($families as [$name,$slug,$reference,$excerpt,$variants]) {
            $p=Product::updateOrCreate(['slug'=>$slug],['category_id'=>$cats['domotica']->id,'name'=>$name,'reference'=>$reference,'excerpt'=>$excerpt,'description'=>'Família do Catálogo Smart Home Tropikal 2026. Cores, protocolos, caixas e compatibilidades são confirmados na proposta.','is_published'=>true]);
            foreach($variants as $i=>$variant) $p->variants()->updateOrCreate(['name'=>$variant],['sort_order'=>$i,'options'=>['protocolo'=>'Zigbee / Tuya','disponibilidade'=>'Sob confirmação']]);
            $p->documents()->updateOrCreate(['path'=>'assets/manuals/00000405-Catalogo_Tropikal_Acores_Completo.pdf'],['title'=>'Catálogo Smart Home Tropikal 2026']);
        }
        $panel=Product::where('slug','solarspace-700wp')->first(); foreach([695,700,705,710,715] as $i=>$watts) $panel?->variants()->updateOrCreate(['sku'=>'SS9-66HD-'.$watts.'N'],['name'=>$watts.' W','sort_order'=>$i,'options'=>['potência'=>$watts.' W','tecnologia'=>'N-TOPCon bifacial dual glass']]);
        $inverter=Product::where('slug','deye-sun-6-8k')->first(); foreach(['3.6','5','6','7','7.6','8','10'] as $i=>$kw) $inverter?->variants()->updateOrCreate(['sku'=>'SUN-'.$kw.'K-SG05LP1-EU-AM2-P'],['name'=>$kw.' kW','sort_order'=>$i,'options'=>['fase'=>'Monofásico','proteção'=>'IP65']]);

        $services = [
            'instalacao-assistencia'=>'Instalação e assistência técnica', 'projetos-energia-climatizacao-domotica'=>'Projetos solares, climatização e domótica',
            'construcao-chave-na-mao'=>'Construção eficiente chave na mão', 'mao-de-obra-especializada'=>'Mão de obra especializada',
            'canal-profissional'=>'Fornecimento para instaladores e revendedores',
        ];
        foreach($services as $slug=>$title) Page::updateOrCreate(['slug'=>$slug],['title'=>$title,'content'=>'<p>Soluções Tropikal nos Açores e na Madeira, sujeitas a avaliação técnica, disponibilidade e confirmação de condições.</p><h2>Acompanhamento especializado</h2><p>Do levantamento inicial à instalação e assistência, com uma proposta adaptada ao projeto.</p>','is_published'=>true]);
        foreach(['email'=>'nuno@tropikal.pt','phone'=>'+351 962 009 898','company_name'=>'Tropikal Service Açores Lda','hero_title'=>'Energia inteligente para um futuro sustentável','hero_text'=>'Energia solar, climatização, domótica e construção com técnicos qualificados nos Açores.'] as $key=>$value) SiteSetting::updateOrCreate(['key'=>$key],['label'=>ucfirst(str_replace('_',' ',$key)),'value'=>$value]);
        foreach(['privacidade'=>'Política de Privacidade','termos'=>'Termos e Condições','cookies'=>'Política de Cookies'] as $slug=>$title) Page::updateOrCreate(['slug'=>$slug],['title'=>$title,'content'=>'<h2>'.$title.'</h2><p>Esta página pode ser atualizada no painel de administração Filament.</p>','is_published'=>true]);
        $this->call(LatestTropikalProductsSeeder::class);
    }
}
