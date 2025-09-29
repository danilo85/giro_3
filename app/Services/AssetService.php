<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetTag;
use App\Models\DownloadLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetService
{
    protected FileStorageService $fileStorageService;
    protected ThumbnailService $thumbnailService;
    protected ZipService $zipService;
    
    public function __construct(
        FileStorageService $fileStorageService,
        ThumbnailService $thumbnailService,
        ZipService $zipService
    ) {
        $this->fileStorageService = $fileStorageService;
        $this->thumbnailService = $thumbnailService;
        $this->zipService = $zipService;
    }
    
    /**
     * Processa upload de múltiplos arquivos
     */
    public function uploadMultipleFiles(array $files, array $tags = []): array
    {
        $uploadedAssets = [];
        
        foreach ($files as $file) {
            $asset = $this->uploadSingleFile($file, $tags);
            $uploadedAssets[] = $asset;
        }
        
        return $uploadedAssets;
    }
    
    /**
     * Processa upload de um arquivo individual
     */
    public function uploadSingleFile(UploadedFile $file, array $tags = []): Asset
    {
        // Valida o arquivo
        $this->validateFile($file);
        
        // Obtém informações do arquivo
        $fileInfo = $this->extractFileInfo($file);
        
        // Armazena o arquivo
        $storagePath = $this->fileStorageService->store($file, $fileInfo['type']);
        
        // Cria thumbnail se for imagem
        $thumbnailPath = null;
        if ($fileInfo['type'] === 'image' && $this->thumbnailService->canCreateThumbnail($fileInfo['extension'])) {
            $thumbnailPath = $this->thumbnailService->create($storagePath, $fileInfo['type']);
        }
        
        // Obtém dimensões para imagens
        $dimensions = null;
        if ($fileInfo['type'] === 'image') {
            $dimensions = $this->getImageDimensions($storagePath);
        }
        
        // Cria o registro do asset
        $asset = Asset::create([
            'user_id' => Auth::id(),
            'original_name' => $fileInfo['original_name'],
            'stored_name' => basename($storagePath),
            'file_path' => $storagePath,
            'thumbnail_path' => $thumbnailPath,
            'type' => $fileInfo['type'],
            'format' => $fileInfo['extension'],
            'mime_type' => $fileInfo['mime_type'],
            'file_size' => $fileInfo['size'],
            'dimensions' => $dimensions
        ]);
        
        // Adiciona tags se fornecidas
        if (!empty($tags)) {
            $this->addTagsToAsset($asset, $tags);
        }
        
        return $asset->load('tags');
    }
    
    /**
     * Busca assets por termo
     */
    public function searchAssets(string $term, ?string $type = null, ?string $format = null, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        $query = Asset::byUser(Auth::id())->search($term)->with('tags');
        
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($format) {
            $query->byFormat($format);
        }
        
        return $query->latest()->limit($limit)->get();
    }
    
    /**
     * Busca assets por lista de nomes
     */
    public function findAssetsByNames(array $names): \Illuminate\Database\Eloquent\Collection
    {
        return Asset::byUser(Auth::id())
            ->where(function ($query) use ($names) {
                foreach ($names as $name) {
                    $query->orWhere('original_name', 'LIKE', "%{$name}%")
                          ->orWhere('stored_name', 'LIKE', "%{$name}%");
                }
            })
            ->with('tags')
            ->get();
    }
    
    /**
     * Cria arquivo ZIP com assets selecionados
     */
    public function createBatchDownload(array $assetIds): string
    {
        $assets = Asset::byUser(Auth::id())->whereIn('id', $assetIds)->get();
        
        if ($assets->isEmpty()) {
            throw new \Exception('Nenhum asset encontrado');
        }
        
        return $this->zipService->createFromAssets($assets);
    }
    
    /**
     * Registra download de asset
     */
    public function logDownload(Asset $asset, string $type = 'single', ?string $ipAddress = null): void
    {
        $asset->logDownload(Auth::id(), $type, $ipAddress);
    }
    
    /**
     * Deleta asset e arquivos associados
     */
    public function deleteAsset(Asset $asset): bool
    {
        if ($asset->user_id !== Auth::id()) {
            throw new \Exception('Acesso negado');
        }
        
        return $asset->delete();
    }
    
    /**
     * Obtém estatísticas do usuário
     */
    public function getUserStats(): array
    {
        $userId = Auth::id();
        
        return [
            'total_assets' => Asset::byUser($userId)->count(),
            'total_images' => Asset::byUser($userId)->images()->count(),
            'total_fonts' => Asset::byUser($userId)->fonts()->count(),
            'recent_uploads' => Asset::byUser($userId)->latest()->take(5)->get(),
            'popular_tags' => AssetTag::popular(10)->get(),
            'download_stats' => DownloadLog::getDownloadStats($userId),
            'storage_used' => Asset::byUser($userId)->sum('file_size')
        ];
    }
    
    /**
     * Valida arquivo enviado
     */
    private function validateFile(UploadedFile $file): void
    {
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg', 'otf', 'ttf', 'woff', 'woff2'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception('Tipo de arquivo não suportado: ' . $extension);
        }
        
        if ($file->getSize() > 10 * 1024 * 1024) { // 10MB
            throw new \Exception('Arquivo muito grande. Máximo permitido: 10MB');
        }
        
        if (!$file->isValid()) {
            throw new \Exception('Arquivo inválido ou corrompido');
        }
    }
    
    /**
     * Extrai informações do arquivo
     */
    private function extractFileInfo(UploadedFile $file): array
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        
        // Determina o tipo do arquivo
        $imageExtensions = ['png', 'jpg', 'jpeg', 'svg'];
        $fontExtensions = ['otf', 'ttf', 'woff', 'woff2'];
        
        if (in_array($extension, $imageExtensions)) {
            $type = 'image';
        } elseif (in_array($extension, $fontExtensions)) {
            $type = 'font';
        } else {
            throw new \Exception('Tipo de arquivo não suportado');
        }
        
        return [
            'original_name' => $originalName,
            'extension' => $extension,
            'mime_type' => $mimeType,
            'size' => $size,
            'type' => $type
        ];
    }
    
    /**
     * Obtém dimensões da imagem
     */
    private function getImageDimensions(string $filePath): ?array
    {
        try {
            $fullPath = Storage::disk('public')->path($filePath);
            $imageSize = getimagesize($fullPath);
            
            if ($imageSize) {
                return [
                    'width' => $imageSize[0],
                    'height' => $imageSize[1]
                ];
            }
        } catch (\Exception $e) {
            // Ignora erros ao obter dimensões
        }
        
        return null;
    }
    
    /**
     * Adiciona tags ao asset
     */
    private function addTagsToAsset(Asset $asset, array $tags): void
    {
        foreach ($tags as $tagName) {
            $tagName = trim($tagName);
            if (!empty($tagName)) {
                AssetTag::create([
                    'asset_id' => $asset->id,
                    'tag' => $tagName
                ]);
            }
        }
    }
}