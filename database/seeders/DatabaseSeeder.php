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

        User::updateOrCreate(['email'=>'admin@tropikal.pt'],['name'=>'Administrador Tropikal','password'=>Hash::make(env('ADMIN_PASSWORD','ChangeMe-'.bin2hex(random_bytes(10))))]);
        $cats=[]; foreach([['Climatização','climatizacao',null],['Ar Condicionado','ar-condicionado','climatizacao'],['Sistemas Solares','sistemas-solares',null],['Inversores','inversores','sistemas-solares'],['Painéis Solares','paineis-solares','sistemas-solares'],['Estruturas','estruturas','sistemas-solares'],['Baterias','baterias','sistemas-solares'],['Domótica','domotica',null],['Bombas de Calor','bombas-de-calor',null],['Construção','construcao',null],['Pladur','pladur','construcao'],['MDF','mdf','construcao'],['Capoto','capoto','construcao'],['Cozinhas','cozinhas','construcao'],['Vinílico','vinilico','construcao']] as [$name,$slug,$parent]) { $cats[$slug]=Category::updateOrCreate(['slug'=>$slug],['name'=>$name,'parent_id'=>$parent ? $cats[$parent]->id:null,'description'=>'Soluções profissionais Tropikal para particulares, empresas, instaladores e revendedores.','is_published'=>true]); }
        $products=[
          ['bombas-de-calor','Bomba de Calor Tropikal XPRO300','xpro300','XPRO300','Bomba de calor A++ para águas quentes sanitárias.','Equipamento Tropikal com refrigerante R290, controlo Wi-Fi, temperatura de água até 75 °C e proteção por ânodo de magnésio.','bomba-xpro300.png','00000402-Bomba calor Tropikal xpro300.pdf'],
          ['inversores','Inversor Híbrido Deye SUN 10/12K','deye-sun-10-12k','SUN-10/12K-SG02LP1-EU-AM3','Inversor híbrido monofásico para sistemas solares.','Ecrã tátil, proteção IP65, ligação AC a sistemas existentes e suporte de várias baterias em paralelo.','inversor-deye-10-12k.png','00000403-SUN-10-12K-SG02LP1-EU-AM3.pdf'],
          ['inversores','Inversor Híbrido Deye SUN 6/8K','deye-sun-6-8k','SUN-6K8K-SG05LP1-EU-AM2-P','Inversor híbrido monofásico para produção e armazenamento solar.','Solução Deye para autoconsumo, armazenamento e gestão inteligente de energia.','inversor-deye-6-8k.png','00000404-SUN-6K8K-SG05LP1-EU-AM2-P.pdf'],
          ['ar-condicionado','Ar Condicionado Série Eco','ar-condicionado-eco','MS-12/18/24HVCJ-ECO','Climatização Tropikal eficiente para habitação e comércio.','Série Eco disponível nas referências MS-12HVCG/ECO, MS-18HVCJ/ECO e MS-24HVCJ/ECO, com instalação por técnicos qualificados.','ar-condicionado-eco.jpg','Ficha-Tecnica-Ar-Condicionado-Eco.pdf'],
          ['paineis-solares','Painel Solarspace Lumina II 700 Wp','solarspace-700wp','SS9-66HD 695-715N','Módulo bifacial N-TOPCon de alta potência.','Painel Solarspace Lumina II com potência máxima até 715 W, eficiência até 23,02% e garantia linear de 30 anos.','painel-solarspace-700wp.png','00000407-Solarspace-700wp data.pdf'],
        ]; foreach($products as [$cat,$name,$slug,$ref,$excerpt,$desc,$image,$doc]) { $p=Product::updateOrCreate(['slug'=>$slug],['category_id'=>$cats[$cat]->id,'name'=>$name,'reference'=>$ref,'excerpt'=>$excerpt,'description'=>$desc,'is_featured'=>true,'is_published'=>true]); $p->images()->updateOrCreate(['path'=>'assets/img/products/'.$image],['alt_text'=>$name]); $p->documents()->updateOrCreate(['path'=>'assets/manuals/'.$doc],['title'=>'Ficha técnica / manual']); }
        $smart=[['Painéis de Controlo Smart Home','paineis-controlo-smart-home','smart-home-paineis.jpg'],['Interruptores Inteligentes Y Series','interruptores-y-series','interruptores-y-series.jpg'],['Interruptores Inteligentes D Series','interruptores-d-series','interruptores-d-series.jpg'],['Termóstato Touch Inteligente','termostato-touch-inteligente','termostato-touch.jpg'],['Gateway Smart Home','gateway-smart-home','gateway-smart.jpg'],['Sistema Inteligente de Cortinas','sistema-inteligente-cortinas','cortinas-smart.jpg']]; foreach($smart as [$name,$slug,$image]) { $p=Product::updateOrCreate(['slug'=>$slug],['category_id'=>$cats['domotica']->id,'name'=>$name,'excerpt'=>'Solução de domótica Tropikal para controlo e automação do espaço.','description'=>'Produto apresentado no Catálogo Smart Home Tropikal 2026. Consulte o catálogo ou peça informação para conhecer modelos, acabamentos e compatibilidades disponíveis.','is_published'=>true]); $p->images()->updateOrCreate(['path'=>'assets/img/products/'.$image],['alt_text'=>$name]); $p->documents()->updateOrCreate(['path'=>'assets/manuals/00000405-Catalogo_Tropikal_Acores_Completo.pdf'],['title'=>'Catálogo Smart Home Tropikal 2026']); }
        foreach(['email'=>'nuno@tropikal.pt','phone'=>'+351 962 009 898','company_name'=>'Tropikal Service Açores Lda','hero_title'=>'Energia inteligente para um futuro sustentável','hero_text'=>'Energia solar, climatização, domótica e construção com técnicos qualificados nos Açores.'] as $key=>$value) SiteSetting::updateOrCreate(['key'=>$key],['label'=>ucfirst(str_replace('_',' ',$key)),'value'=>$value]);
        foreach(['privacidade'=>'Política de Privacidade','termos'=>'Termos e Condições','cookies'=>'Política de Cookies'] as $slug=>$title) Page::updateOrCreate(['slug'=>$slug],['title'=>$title,'content'=>'<h2>'.$title.'</h2><p>Esta página pode ser atualizada no painel de administração Filament.</p>','is_published'=>true]);
    }
}
