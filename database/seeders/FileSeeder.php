<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class FileSeeder extends Seeder
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

        // Verificar se existem categorias de arquivo
        $categories = DB::table('file_categories')->get();
        
        $files = [];
        $tempFiles = [];
        $sharedLinks = [];
        $accessLogs = [];
        
        // Tipos de arquivos de exemplo
        $fileTypes = [
            ['name' => 'documento-importante.pdf', 'mime' => 'application/pdf', 'size' => rand(100000, 5000000)],
            ['name' => 'planilha-financeira.xlsx', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'size' => rand(50000, 2000000)],
            ['name' => 'apresentacao-projeto.pptx', 'mime' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'size' => rand(1000000, 10000000)],
            ['name' => 'contrato-servicos.docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'size' => rand(50000, 1000000)],
            ['name' => 'backup-dados.zip', 'mime' => 'application/zip', 'size' => rand(5000000, 50000000)],
            ['name' => 'video-promocional.mp4', 'mime' => 'video/mp4', 'size' => rand(10000000, 100000000)],
            ['name' => 'audio-podcast.mp3', 'mime' => 'audio/mpeg', 'size' => rand(5000000, 50000000)],
        ];
        
        foreach ($users as $user) {
            foreach ($fileTypes as $index => $fileType) {
                $storedName = 'file_' . time() . '_' . $index . '_' . Str::random(8) . '.' . pathinfo($fileType['name'], PATHINFO_EXTENSION);
                
                $files[] = [
                    'user_id' => $user->id,
                    'category_id' => $categories->isNotEmpty() ? $categories->random()->id : null,
                    'original_name' => $fileType['name'],
                    'stored_name' => $storedName,
                    'mime_type' => $fileType['mime'],
                    'file_size' => $fileType['size'],
                    'is_temporary' => rand(0, 10) > 8, // 20% chance de ser temporário
                    'description' => 'Arquivo de exemplo para demonstração',
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 5)),
                ];
            }
            
            // Criar alguns arquivos temporários
            for ($i = 0; $i < rand(2, 5); $i++) {
                $tempFiles[] = [
                    'temp_id' => Str::random(32),
                    'user_id' => $user->id,
                    'nome_arquivo' => 'temp_file_' . $i . '.tmp',
                    'url_arquivo' => 'temp/' . $user->id . '/temp_' . time() . '_' . $i . '.tmp',
                    'tipo_arquivo' => 'application/octet-stream',
                    'tamanho' => rand(1000, 100000),
                    'created_at' => now()->subHours(rand(1, 12)),
                    'updated_at' => now()->subHours(rand(0, 6)),
                ];
            }
        }
        
        // Inserir dados em lotes
        if (!empty($files)) {
            DB::table('files')->insert($files);
        }
        
        if (!empty($tempFiles)) {
            DB::table('temp_files')->insert($tempFiles);
        }
        
        $this->command->info('Arquivos criados com sucesso!');
        $this->command->info('Total de arquivos criados: ' . count($files));
        $this->command->info('Total de arquivos temporários criados: ' . count($tempFiles));
    }
}
