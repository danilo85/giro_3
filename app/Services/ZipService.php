<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\Asset;

class ZipService
{
    private FileStorageService $fileStorageService;
    
    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }
    
    /**
     * Cria um arquivo ZIP com múltiplos assets
     */
    public function createZipFromAssets(Collection $assets, string $zipName = null): ?string
    {
        if ($assets->isEmpty()) {
            return null;
        }
        
        try {
            // Gera nome do ZIP se não fornecido
            if (!$zipName) {
                $zipName = 'assets_' . date('Y-m-d_H-i-s') . '.zip';
            }
            
            // Garante extensão .zip
            if (!str_ends_with(strtolower($zipName), '.zip')) {
                $zipName .= '.zip';
            }
            
            // Caminho temporário para o ZIP
            $tempPath = 'temp/' . $zipName;
            $fullTempPath = $this->fileStorageService->getFullPath($tempPath);
            
            // Garante que o diretório temp existe
            $this->fileStorageService->ensureDirectoryExists('temp');
            
            // Cria o arquivo ZIP
            $zip = new ZipArchive();
            $result = $zip->open($fullTempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            
            if ($result !== TRUE) {
                \Log::error('Erro ao criar arquivo ZIP: ' . $this->getZipError($result));
                return null;
            }
            
            // Adiciona cada asset ao ZIP
            foreach ($assets as $asset) {
                $this->addAssetToZip($zip, $asset);
            }
            
            // Fecha o ZIP
            $zip->close();
            
            return $tempPath;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao criar ZIP: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cria ZIP de assets por IDs
     */
    public function createZipFromAssetIds(array $assetIds, string $zipName = null): ?string
    {
        $assets = Asset::whereIn('id', $assetIds)->get();
        return $this->createZipFromAssets($assets, $zipName);
    }
    
    /**
     * Cria ZIP de assets por tipo
     */
    public function createZipByType(string $type, string $zipName = null): ?string
    {
        $assets = Asset::where('type', $type)->get();
        
        if (!$zipName) {
            $zipName = $type . '_assets_' . date('Y-m-d_H-i-s') . '.zip';
        }
        
        return $this->createZipFromAssets($assets, $zipName);
    }
    
    /**
     * Cria ZIP de assets por usuário
     */
    public function createZipByUser(int $userId, string $zipName = null): ?string
    {
        $assets = Asset::where('user_id', $userId)->get();
        
        if (!$zipName) {
            $zipName = 'user_' . $userId . '_assets_' . date('Y-m-d_H-i-s') . '.zip';
        }
        
        return $this->createZipFromAssets($assets, $zipName);
    }
    
    /**
     * Cria ZIP de assets por tags
     */
    public function createZipByTags(array $tags, string $zipName = null): ?string
    {
        $assets = Asset::whereHas('tags', function ($query) use ($tags) {
            $query->whereIn('tag', $tags);
        })->get();
        
        if (!$zipName) {
            $zipName = 'tagged_assets_' . date('Y-m-d_H-i-s') . '.zip';
        }
        
        return $this->createZipFromAssets($assets, $zipName);
    }
    
    /**
     * Adiciona arquivos de uma pasta ao ZIP
     */
    public function createZipFromDirectory(string $directory, string $zipName = null, array $excludeExtensions = []): ?string
    {
        try {
            if (!$this->fileStorageService->exists($directory)) {
                return null;
            }
            
            // Gera nome do ZIP se não fornecido
            if (!$zipName) {
                $dirName = basename($directory);
                $zipName = $dirName . '_' . date('Y-m-d_H-i-s') . '.zip';
            }
            
            // Garante extensão .zip
            if (!str_ends_with(strtolower($zipName), '.zip')) {
                $zipName .= '.zip';
            }
            
            // Caminho temporário para o ZIP
            $tempPath = 'temp/' . $zipName;
            $fullTempPath = $this->fileStorageService->getFullPath($tempPath);
            
            // Garante que o diretório temp existe
            $this->fileStorageService->ensureDirectoryExists('temp');
            
            // Cria o arquivo ZIP
            $zip = new ZipArchive();
            $result = $zip->open($fullTempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            
            if ($result !== TRUE) {
                \Log::error('Erro ao criar arquivo ZIP: ' . $this->getZipError($result));
                return null;
            }
            
            // Lista arquivos do diretório
            $files = $this->fileStorageService->listFiles($directory);
            
            foreach ($files as $file) {
                // Verifica extensões excluídas
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($extension, $excludeExtensions)) {
                    continue;
                }
                
                $fullFilePath = $this->fileStorageService->getFullPath($file);
                $relativePath = str_replace($directory . '/', '', $file);
                
                if (file_exists($fullFilePath)) {
                    $zip->addFile($fullFilePath, $relativePath);
                }
            }
            
            // Fecha o ZIP
            $zip->close();
            
            return $tempPath;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao criar ZIP do diretório: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Extrai um arquivo ZIP
     */
    public function extractZip(string $zipPath, string $extractTo): bool
    {
        try {
            $fullZipPath = $this->fileStorageService->getFullPath($zipPath);
            $fullExtractPath = $this->fileStorageService->getFullPath($extractTo);
            
            if (!file_exists($fullZipPath)) {
                return false;
            }
            
            // Garante que o diretório de extração existe
            $this->fileStorageService->ensureDirectoryExists($extractTo);
            
            $zip = new ZipArchive();
            $result = $zip->open($fullZipPath);
            
            if ($result !== TRUE) {
                \Log::error('Erro ao abrir arquivo ZIP: ' . $this->getZipError($result));
                return false;
            }
            
            // Extrai o ZIP
            $extracted = $zip->extractTo($fullExtractPath);
            $zip->close();
            
            return $extracted;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao extrair ZIP: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lista conteúdo de um arquivo ZIP
     */
    public function listZipContents(string $zipPath): ?array
    {
        try {
            $fullZipPath = $this->fileStorageService->getFullPath($zipPath);
            
            if (!file_exists($fullZipPath)) {
                return null;
            }
            
            $zip = new ZipArchive();
            $result = $zip->open($fullZipPath);
            
            if ($result !== TRUE) {
                \Log::error('Erro ao abrir arquivo ZIP: ' . $this->getZipError($result));
                return null;
            }
            
            $contents = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $contents[] = [
                    'name' => $stat['name'],
                    'size' => $stat['size'],
                    'compressed_size' => $stat['comp_size'],
                    'modified' => date('Y-m-d H:i:s', $stat['mtime'])
                ];
            }
            
            $zip->close();
            
            return $contents;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao listar conteúdo do ZIP: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Remove arquivos ZIP temporários antigos
     */
    public function cleanupTempZips(int $hoursOld = 24): int
    {
        try {
            $tempFiles = $this->fileStorageService->listFiles('temp');
            $deletedCount = 0;
            $cutoffTime = now()->subHours($hoursOld)->timestamp;
            
            foreach ($tempFiles as $file) {
                // Verifica apenas arquivos ZIP
                if (!str_ends_with(strtolower($file), '.zip')) {
                    continue;
                }
                
                $lastModified = Storage::disk('public')->lastModified($file);
                
                if ($lastModified < $cutoffTime) {
                    if ($this->fileStorageService->delete($file)) {
                        $deletedCount++;
                    }
                }
            }
            
            return $deletedCount;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao limpar ZIPs temporários: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtém informações de um arquivo ZIP
     */
    public function getZipInfo(string $zipPath): ?array
    {
        try {
            $fullZipPath = $this->fileStorageService->getFullPath($zipPath);
            
            if (!file_exists($fullZipPath)) {
                return null;
            }
            
            $zip = new ZipArchive();
            $result = $zip->open($fullZipPath);
            
            if ($result !== TRUE) {
                return null;
            }
            
            $info = [
                'file_count' => $zip->numFiles,
                'file_size' => filesize($fullZipPath),
                'comment' => $zip->comment,
                'created' => date('Y-m-d H:i:s', filectime($fullZipPath)),
                'modified' => date('Y-m-d H:i:s', filemtime($fullZipPath))
            ];
            
            $zip->close();
            
            return $info;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao obter informações do ZIP: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Adiciona um asset ao arquivo ZIP
     */
    private function addAssetToZip(ZipArchive $zip, Asset $asset): bool
    {
        try {
            $filePath = $asset->file_path;
            $fullPath = $this->fileStorageService->getFullPath($filePath);
            
            if (!file_exists($fullPath)) {
                \Log::warning('Arquivo não encontrado para asset: ' . $asset->id);
                return false;
            }
            
            // Nome do arquivo no ZIP (mantém nome original)
            $zipFileName = $asset->original_name;
            
            // Verifica se já existe arquivo com mesmo nome no ZIP
            $counter = 1;
            $baseName = pathinfo($zipFileName, PATHINFO_FILENAME);
            $extension = pathinfo($zipFileName, PATHINFO_EXTENSION);
            
            while ($zip->locateName($zipFileName) !== false) {
                $zipFileName = $baseName . '_' . $counter . '.' . $extension;
                $counter++;
            }
            
            // Adiciona o arquivo ao ZIP
            $added = $zip->addFile($fullPath, $zipFileName);
            
            if (!$added) {
                \Log::warning('Falha ao adicionar arquivo ao ZIP: ' . $asset->id);
            }
            
            return $added;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao adicionar asset ao ZIP: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtém mensagem de erro do ZipArchive
     */
    private function getZipError(int $code): string
    {
        return match ($code) {
            ZipArchive::ER_OK => 'Sem erro',
            ZipArchive::ER_MULTIDISK => 'Multi-disk zip archives não suportado',
            ZipArchive::ER_RENAME => 'Erro ao renomear arquivo temporário',
            ZipArchive::ER_CLOSE => 'Erro ao fechar arquivo',
            ZipArchive::ER_SEEK => 'Erro de seek',
            ZipArchive::ER_READ => 'Erro de leitura',
            ZipArchive::ER_WRITE => 'Erro de escrita',
            ZipArchive::ER_CRC => 'Erro de CRC',
            ZipArchive::ER_ZIPCLOSED => 'Arquivo ZIP fechado',
            ZipArchive::ER_NOENT => 'Arquivo não existe',
            ZipArchive::ER_EXISTS => 'Arquivo já existe',
            ZipArchive::ER_OPEN => 'Não é possível abrir arquivo',
            ZipArchive::ER_TMPOPEN => 'Falha ao criar arquivo temporário',
            ZipArchive::ER_ZLIB => 'Erro Zlib',
            ZipArchive::ER_MEMORY => 'Erro de memória',
            ZipArchive::ER_CHANGED => 'Entry foi alterado',
            ZipArchive::ER_COMPNOTSUPP => 'Método de compressão não suportado',
            ZipArchive::ER_EOF => 'Final de arquivo prematuro',
            ZipArchive::ER_INVAL => 'Argumento inválido',
            ZipArchive::ER_NOZIP => 'Não é um arquivo zip',
            ZipArchive::ER_INTERNAL => 'Erro interno',
            ZipArchive::ER_INCONS => 'Arquivo ZIP inconsistente',
            ZipArchive::ER_REMOVE => 'Não é possível remover arquivo',
            ZipArchive::ER_DELETED => 'Entry foi deletado',
            default => 'Erro desconhecido: ' . $code
        };
    }
}