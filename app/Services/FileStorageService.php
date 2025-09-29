<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService
{
    /**
     * Armazena um arquivo no disco público
     */
    public function store(UploadedFile $file, string $type): string
    {
        $directory = $this->getDirectoryByType($type);
        $fileName = $this->generateUniqueFileName($file, $directory);
        
        // Cria o diretório se não existir
        $this->ensureDirectoryExists($directory);
        
        // Armazena o arquivo
        $filePath = $file->storeAs($directory, $fileName, 'public');
        
        return $filePath;
    }
    
    /**
     * Remove um arquivo do armazenamento
     */
    public function delete(string $filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }
        
        return true; // Considera sucesso se o arquivo já não existe
    }
    
    /**
     * Verifica se um arquivo existe
     */
    public function exists(string $filePath): bool
    {
        return Storage::disk('public')->exists($filePath);
    }
    
    /**
     * Obtém o caminho completo do arquivo
     */
    public function getFullPath(string $filePath): string
    {
        return Storage::disk('public')->path($filePath);
    }
    
    /**
     * Obtém a URL pública do arquivo
     */
    public function getPublicUrl(string $filePath): string
    {
        return Storage::disk('public')->url($filePath);
    }
    
    /**
     * Copia um arquivo para outro local
     */
    public function copy(string $sourcePath, string $destinationPath): bool
    {
        // Garante que o diretório de destino existe
        $destinationDir = dirname($destinationPath);
        $this->ensureDirectoryExists($destinationDir);
        
        return Storage::disk('public')->copy($sourcePath, $destinationPath);
    }
    
    /**
     * Move um arquivo para outro local
     */
    public function move(string $sourcePath, string $destinationPath): bool
    {
        // Garante que o diretório de destino existe
        $destinationDir = dirname($destinationPath);
        $this->ensureDirectoryExists($destinationDir);
        
        return Storage::disk('public')->move($sourcePath, $destinationPath);
    }
    
    /**
     * Obtém informações do arquivo
     */
    public function getFileInfo(string $filePath): ?array
    {
        if (!$this->exists($filePath)) {
            return null;
        }
        
        $fullPath = $this->getFullPath($filePath);
        
        return [
            'size' => Storage::disk('public')->size($filePath),
            'last_modified' => Storage::disk('public')->lastModified($filePath),
            'mime_type' => Storage::disk('public')->mimeType($filePath),
            'full_path' => $fullPath,
            'public_url' => $this->getPublicUrl($filePath)
        ];
    }
    
    /**
     * Lista arquivos em um diretório
     */
    public function listFiles(string $directory): array
    {
        return Storage::disk('public')->files($directory);
    }
    
    /**
     * Obtém o tamanho total de um diretório
     */
    public function getDirectorySize(string $directory): int
    {
        $files = $this->listFiles($directory);
        $totalSize = 0;
        
        foreach ($files as $file) {
            $totalSize += Storage::disk('public')->size($file);
        }
        
        return $totalSize;
    }
    
    /**
     * Limpa arquivos antigos de um diretório
     */
    public function cleanOldFiles(string $directory, int $daysOld = 30): int
    {
        $files = $this->listFiles($directory);
        $deletedCount = 0;
        $cutoffTime = now()->subDays($daysOld)->timestamp;
        
        foreach ($files as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);
            
            if ($lastModified < $cutoffTime) {
                if ($this->delete($file)) {
                    $deletedCount++;
                }
            }
        }
        
        return $deletedCount;
    }
    
    /**
     * Cria estrutura de diretórios para assets
     */
    public function createAssetDirectories(): void
    {
        $directories = [
            'assets/images',
            'assets/fonts',
            'assets/thumbnails/images',
            'assets/thumbnails/fonts',
            'temp'
        ];
        
        foreach ($directories as $directory) {
            $this->ensureDirectoryExists($directory);
        }
    }
    
    /**
     * Obtém diretório baseado no tipo de arquivo
     */
    private function getDirectoryByType(string $type): string
    {
        return match ($type) {
            'image' => 'assets/images',
            'font' => 'assets/fonts',
            default => 'assets/misc'
        };
    }
    
    /**
     * Gera nome único para arquivo evitando duplicatas
     */
    private function generateUniqueFileName(UploadedFile $file, string $directory): string
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Limpa o nome do arquivo
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = Str::slug($baseName);
        
        // Se o nome ficar vazio após a limpeza, usa um nome padrão
        if (empty($baseName)) {
            $baseName = 'file_' . time();
        }
        
        $fileName = $baseName . '.' . $extension;
        
        // Verifica se já existe e adiciona contador se necessário
        $counter = 1;
        while (Storage::disk('public')->exists($directory . '/' . $fileName)) {
            $fileName = $baseName . '_' . $counter . '.' . $extension;
            $counter++;
        }
        
        return $fileName;
    }
    
    /**
     * Garante que um diretório existe
     */
    private function ensureDirectoryExists(string $directory): void
    {
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory, 0755, true);
        }
    }
    
    /**
     * Valida se um caminho está dentro dos diretórios permitidos
     */
    public function isPathAllowed(string $path): bool
    {
        $allowedPaths = [
            'assets/',
            'temp/'
        ];
        
        foreach ($allowedPaths as $allowedPath) {
            if (str_starts_with($path, $allowedPath)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sanitiza nome de arquivo
     */
    public function sanitizeFileName(string $fileName): string
    {
        // Remove caracteres perigosos
        $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
        
        // Remove múltiplos underscores consecutivos
        $fileName = preg_replace('/_+/', '_', $fileName);
        
        // Remove underscores do início e fim
        $fileName = trim($fileName, '_');
        
        return $fileName;
    }
}