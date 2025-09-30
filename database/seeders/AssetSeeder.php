<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('Nenhum usuário encontrado. Execute UserSeeder primeiro.');
            return;
        }

        $assets = [];
        $assetTags = [];
        
        // Tipos de assets de exemplo
        $imageTypes = [
            ['name' => 'logo-exemplo.png', 'format' => 'png', 'mime' => 'image/png'],
            ['name' => 'banner-promocional.jpg', 'format' => 'jpg', 'mime' => 'image/jpeg'],
            ['name' => 'icone-social.svg', 'format' => 'svg', 'mime' => 'image/svg+xml'],
            ['name' => 'foto-produto.png', 'format' => 'png', 'mime' => 'image/png'],
            ['name' => 'background-site.jpg', 'format' => 'jpg', 'mime' => 'image/jpeg'],
        ];
        
        $fontTypes = [
            ['name' => 'Roboto-Regular.ttf', 'format' => 'ttf', 'mime' => 'font/ttf'],
            ['name' => 'OpenSans-Bold.woff2', 'format' => 'woff2', 'mime' => 'font/woff2'],
            ['name' => 'Montserrat-Light.otf', 'format' => 'otf', 'mime' => 'font/otf'],
        ];
        
        $tags = ['logo', 'banner', 'social', 'produto', 'background', 'fonte', 'tipografia', 'branding', 'marketing', 'web'];
        
        foreach ($users as $user) {
            // Criar assets de imagem
            foreach ($imageTypes as $index => $imageType) {
                $assetId = Str::uuid();
                $storedName = 'asset_' . time() . '_' . $index . '.' . $imageType['format'];
                
                $assets[] = [
                    'id' => $assetId,
                    'user_id' => $user->id,
                    'original_name' => $imageType['name'],
                    'stored_name' => $storedName,
                    'file_path' => 'assets/images/' . $storedName,
                    'thumbnail_path' => 'assets/thumbnails/images/thumb_' . $storedName,
                    'type' => 'image',
                    'format' => $imageType['format'],
                    'mime_type' => $imageType['mime'],
                    'file_size' => rand(50000, 2000000), // 50KB a 2MB
                    'metadata' => json_encode([
                        'color_profile' => 'sRGB',
                        'compression' => 'lossless'
                    ]),
                    'dimensions' => json_encode([
                        'width' => rand(800, 1920),
                        'height' => rand(600, 1080)
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Adicionar tags aleatórias
                $randomTags = array_rand($tags, rand(2, 4));
                foreach ((array)$randomTags as $tagIndex) {
                    $assetTags[] = [
                        'id' => Str::uuid(),
                        'asset_id' => $assetId,
                        'tag_name' => $tags[$tagIndex],
                        'created_at' => now(),
                    ];
                }
            }
            
            // Criar assets de fonte
            foreach ($fontTypes as $index => $fontType) {
                $assetId = Str::uuid();
                $storedName = 'font_' . time() . '_' . $index . '.' . $fontType['format'];
                
                $assets[] = [
                    'id' => $assetId,
                    'user_id' => $user->id,
                    'original_name' => $fontType['name'],
                    'stored_name' => $storedName,
                    'file_path' => 'assets/fonts/' . $storedName,
                    'thumbnail_path' => null,
                    'type' => 'font',
                    'format' => $fontType['format'],
                    'mime_type' => $fontType['mime'],
                    'file_size' => rand(100000, 500000), // 100KB a 500KB
                    'metadata' => json_encode([
                        'font_family' => explode('-', $fontType['name'])[0],
                        'font_weight' => 'normal'
                    ]),
                    'dimensions' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Adicionar tags de fonte
                $fontTags = ['fonte', 'tipografia', 'web'];
                foreach ($fontTags as $tag) {
                    $assetTags[] = [
                        'id' => Str::uuid(),
                        'asset_id' => $assetId,
                        'tag_name' => $tag,
                        'created_at' => now(),
                    ];
                }
            }
        }
        
        // Inserir dados em lotes
        DB::table('assets')->insert($assets);
        DB::table('asset_tags')->insert($assetTags);
        
        $this->command->info('Assets criados com sucesso!');
        $this->command->info('Total de assets criados: ' . count($assets));
        $this->command->info('Total de tags criadas: ' . count($assetTags));
    }
}
