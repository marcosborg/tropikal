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
          ['bombas-de-calor','Bomba de Calor Tropikal XPRO300','xpro300','XPRO300','Solução eficiente para aquecimento de águas sanitárias.','Bomba de calor Tropikal concebida para elevada eficiência e conforto.','bombas-de-calor.png','Bomba calor Tropikal xpro300.pdf'],
          ['inversores','Inversor Híbrido Deye SUN 10/12K','deye-sun-10-12k','SUN-10/12K-SG02LP1-EU-AM3','Inversor híbrido monofásico para sistemas solares.','Consulte a ficha técnica anexada para potências, compatibilidades e condições de instalação.','paineis-fotovoltaicos.png','SUN-10-12K-SG02LP1-EU-AM3.pdf'],
          ['inversores','Inversor Híbrido Deye SUN 6/8K','deye-sun-6-8k','SUN-6K8K-SG05LP1-EU-AM2-P','Inversor híbrido monofásico para produção e armazenamento solar.','Consulte a ficha técnica anexada para todas as especificações.','paineis-fotovoltaicos.png','SUN-6K8K-SG05LP1-EU-AM2-P.pdf'],
          ['ar-condicionado','Ar Condicionado Eco','ar-condicionado-eco','ECO','Climatização eficiente para habitação e comércio.','Equipamento de ar condicionado com instalação por técnicos qualificados.','ar-condicionado.png','Ficha-Te__cnica-Ar-Condicionado-Eco_1_.pdf'],
          ['paineis-solares','Painel Solarspace 700 Wp','solarspace-700wp','700WP','Painel fotovoltaico de alta potência para sistemas solares.','Consulte a ficha técnica para dados elétricos, mecânicos e condições de garantia.','paineis-fotovoltaicos.png','Solarspace-700wp data.pdf'],
        ]; foreach($products as [$cat,$name,$slug,$ref,$excerpt,$desc,$image,$doc]) { $p=Product::updateOrCreate(['slug'=>$slug],['category_id'=>$cats[$cat]->id,'name'=>$name,'reference'=>$ref,'excerpt'=>$excerpt,'description'=>$desc,'is_featured'=>true,'is_published'=>true]); $p->images()->updateOrCreate(['path'=>'products/'.$image],['alt_text'=>$name]); $p->documents()->updateOrCreate(['path'=>'manuals/'.$doc],['title'=>'Ficha técnica / manual']); }
        $smart=[['Painéis de Controlo Smart Home','paineis-controlo-smart-home','smart-home-paineis.jpg'],['Interruptores Inteligentes Y Series','interruptores-y-series','interruptores-y-series.jpg'],['Interruptores Inteligentes D Series','interruptores-d-series','interruptores-d-series.jpg'],['Termóstato Touch Inteligente','termostato-touch-inteligente','termostato-touch.jpg'],['Gateway Smart Home','gateway-smart-home','gateway-smart.jpg'],['Sistema Inteligente de Cortinas','sistema-inteligente-cortinas','cortinas-smart.jpg']]; foreach($smart as [$name,$slug,$image]) { $p=Product::updateOrCreate(['slug'=>$slug],['category_id'=>$cats['domotica']->id,'name'=>$name,'excerpt'=>'Solução de domótica Tropikal para controlo e automação do espaço.','description'=>'Produto apresentado no Catálogo Smart Home Tropikal 2026. Consulte o catálogo ou peça informação para conhecer modelos, acabamentos e compatibilidades disponíveis.','is_published'=>true]); $p->images()->updateOrCreate(['path'=>'products/'.$image],['alt_text'=>$name]); $p->documents()->updateOrCreate(['path'=>'manuals/Catalogo_Tropikal_Acores_Completo.pdf'],['title'=>'Catálogo Smart Home Tropikal 2026']); }
        foreach(['email'=>'nuno@tropikal.pt','phone'=>'+351 962 009 898','company_name'=>'Tropikal Service Açores Lda','hero_title'=>'Energia inteligente para um futuro sustentável','hero_text'=>'Energia solar, climatização, domótica e construção com técnicos qualificados nos Açores.'] as $key=>$value) SiteSetting::updateOrCreate(['key'=>$key],['label'=>ucfirst(str_replace('_',' ',$key)),'value'=>$value]);
        foreach(['privacidade'=>'Política de Privacidade','termos'=>'Termos e Condições','cookies'=>'Política de Cookies'] as $slug=>$title) Page::updateOrCreate(['slug'=>$slug],['title'=>$title,'content'=>'<h2>'.$title.'</h2><p>Esta página pode ser atualizada no painel de administração Filament.</p>','is_published'=>true]);
    }
}
