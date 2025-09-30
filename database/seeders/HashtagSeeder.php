<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HashtagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hashtags = [
            // Marketing Digital
            ['name' => 'marketingdigital', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'socialmedia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'instagram', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'facebook', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'linkedin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'tiktok', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'youtube', 'created_at' => now(), 'updated_at' => now()],
            
            // Design
            ['name' => 'design', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'branding', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'logo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'identidadevisual', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'designgrafico', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ux', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ui', 'created_at' => now(), 'updated_at' => now()],
            
            // Desenvolvimento
            ['name' => 'webdesign', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'desenvolvimento', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'website', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ecommerce', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'app', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'mobile', 'created_at' => now(), 'updated_at' => now()],
            
            // Negócios
            ['name' => 'empreendedorismo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'startup', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'negocios', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'vendas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'marketing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'publicidade', 'created_at' => now(), 'updated_at' => now()],
            
            // Tecnologia
            ['name' => 'tecnologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'inovacao', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'digital', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'automacao', 'created_at' => now(), 'updated_at' => now()],
            
            // Conteúdo
            ['name' => 'conteudo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'copywriting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'storytelling', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'video', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'fotografia', 'created_at' => now(), 'updated_at' => now()],
            
            // Tendências
            ['name' => 'trending', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'viral', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'inspiracao', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'criatividade', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'portfolio', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('hashtags')->insert($hashtags);
        
        $this->command->info('Hashtags criadas com sucesso!');
        $this->command->info('Total de hashtags criadas: ' . count($hashtags));
    }
}
