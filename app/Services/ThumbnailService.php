<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\Exception\NotReadableException;

class ThumbnailService
{
    private FileStorageService $fileStorageService;
    
    // Configurações de thumbnail
    private const THUMBNAIL_SIZES = [
        'small' => ['width' => 150, 'height' => 150],
        'medium' => ['width' => 300, 'height' => 300],
        'large' => ['width' => 600, 'height' => 600]
    ];
    
    private const DEFAULT_SIZE = 'medium';
    private const QUALITY = 85;
    
    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }
    
    /**
     * Cria thumbnail para uma imagem
     */
    public function createThumbnail(string $imagePath, string $size = self::DEFAULT_SIZE): ?string
    {
        try {
            if (!$this->isImageFile($imagePath)) {
                return null;
            }
            
            $fullPath = $this->fileStorageService->getFullPath($imagePath);
            
            if (!file_exists($fullPath)) {
                return null;
            }
            
            // Carrega a imagem
            $image = Image::make($fullPath);
            
            // Obtém dimensões do thumbnail
            $dimensions = $this->getThumbnailDimensions($size);
            
            // Redimensiona mantendo proporção
            $image->fit($dimensions['width'], $dimensions['height'], function ($constraint) {
                $constraint->upsize(); // Não aumenta imagens menores
            });
            
            // Gera caminho do thumbnail
            $thumbnailPath = $this->generateThumbnailPath($imagePath, $size);
            $thumbnailFullPath = $this->fileStorageService->getFullPath($thumbnailPath);
            
            // Garante que o diretório existe
            $thumbnailDir = dirname($thumbnailPath);
            $this->fileStorageService->ensureDirectoryExists($thumbnailDir);
            
            // Salva o thumbnail
            $image->save($thumbnailFullPath, self::QUALITY);
            
            return $thumbnailPath;
            
        } catch (NotReadableException $e) {
            \Log::error('Erro ao criar thumbnail - arquivo não legível: ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            \Log::error('Erro ao criar thumbnail: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cria múltiplos tamanhos de thumbnail
     */
    public function createMultipleThumbnails(string $imagePath, array $sizes = ['small', 'medium', 'large']): array
    {
        $thumbnails = [];
        
        foreach ($sizes as $size) {
            $thumbnail = $this->createThumbnail($imagePath, $size);
            if ($thumbnail) {
                $thumbnails[$size] = $thumbnail;
            }
        }
        
        return $thumbnails;
    }
    
    /**
     * Cria thumbnail para fonte (preview de texto)
     */
    public function createFontThumbnail(string $fontPath, string $text = 'Aa Bb Cc', int $fontSize = 48): ?string
    {
        try {
            $fullPath = $this->fileStorageService->getFullPath($fontPath);
            
            if (!file_exists($fullPath)) {
                return null;
            }
            
            // Cria uma imagem em branco
            $width = 400;
            $height = 200;
            $image = Image::canvas($width, $height, '#ffffff');
            
            // Adiciona texto com a fonte
            $image->text($text, $width / 2, $height / 2, function ($font) use ($fullPath, $fontSize) {
                $font->file($fullPath);
                $font->size($fontSize);
                $font->color('#333333');
                $font->align('center');
                $font->valign('middle');
            });
            
            // Gera caminho do thumbnail
            $thumbnailPath = $this->generateFontThumbnailPath($fontPath);
            $thumbnailFullPath = $this->fileStorageService->getFullPath($thumbnailPath);
            
            // Garante que o diretório existe
            $thumbnailDir = dirname($thumbnailPath);
            $this->fileStorageService->ensureDirectoryExists($thumbnailDir);
            
            // Salva o thumbnail
            $image->save($thumbnailFullPath, self::QUALITY);
            
            return $thumbnailPath;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao criar thumbnail de fonte: ' . $e->getMessage());
            
            // Fallback: cria um thumbnail genérico para fontes
            return $this->createGenericFontThumbnail($fontPath);
        }
    }
    
    /**
     * Remove thumbnail(s) de um arquivo
     */
    public function deleteThumbnails(string $originalPath): bool
    {
        $success = true;
        
        // Remove thumbnails de imagem
        foreach (array_keys(self::THUMBNAIL_SIZES) as $size) {
            $thumbnailPath = $this->generateThumbnailPath($originalPath, $size);
            if (!$this->fileStorageService->delete($thumbnailPath)) {
                $success = false;
            }
        }
        
        // Remove thumbnail de fonte
        $fontThumbnailPath = $this->generateFontThumbnailPath($originalPath);
        if (!$this->fileStorageService->delete($fontThumbnailPath)) {
            $success = false;
        }
        
        return $success;
    }
    
    /**
     * Obtém URL do thumbnail
     */
    public function getThumbnailUrl(string $originalPath, string $size = self::DEFAULT_SIZE): ?string
    {
        $thumbnailPath = $this->generateThumbnailPath($originalPath, $size);
        
        if ($this->fileStorageService->exists($thumbnailPath)) {
            return $this->fileStorageService->getPublicUrl($thumbnailPath);
        }
        
        return null;
    }
    
    /**
     * Obtém URL do thumbnail de fonte
     */
    public function getFontThumbnailUrl(string $fontPath): ?string
    {
        $thumbnailPath = $this->generateFontThumbnailPath($fontPath);
        
        if ($this->fileStorageService->exists($thumbnailPath)) {
            return $this->fileStorageService->getPublicUrl($thumbnailPath);
        }
        
        return null;
    }
    
    /**
     * Verifica se um arquivo é uma imagem
     */
    public function isImageFile(string $filePath): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        return in_array($extension, $imageExtensions);
    }
    
    /**
     * Verifica se um arquivo é uma fonte
     */
    public function isFontFile(string $filePath): bool
    {
        $fontExtensions = ['ttf', 'otf', 'woff', 'woff2', 'eot'];
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        return in_array($extension, $fontExtensions);
    }
    
    /**
     * Obtém informações da imagem
     */
    public function getImageInfo(string $imagePath): ?array
    {
        try {
            $fullPath = $this->fileStorageService->getFullPath($imagePath);
            
            if (!file_exists($fullPath) || !$this->isImageFile($imagePath)) {
                return null;
            }
            
            $image = Image::make($fullPath);
            
            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'mime_type' => $image->mime(),
                'file_size' => filesize($fullPath)
            ];
            
        } catch (\Exception $e) {
            \Log::error('Erro ao obter informações da imagem: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Redimensiona uma imagem
     */
    public function resizeImage(string $imagePath, int $width, int $height, bool $maintainAspectRatio = true): ?string
    {
        try {
            $fullPath = $this->fileStorageService->getFullPath($imagePath);
            
            if (!file_exists($fullPath) || !$this->isImageFile($imagePath)) {
                return null;
            }
            
            $image = Image::make($fullPath);
            
            if ($maintainAspectRatio) {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $image->resize($width, $height);
            }
            
            // Gera novo caminho
            $resizedPath = $this->generateResizedPath($imagePath, $width, $height);
            $resizedFullPath = $this->fileStorageService->getFullPath($resizedPath);
            
            // Garante que o diretório existe
            $resizedDir = dirname($resizedPath);
            $this->fileStorageService->ensureDirectoryExists($resizedDir);
            
            // Salva a imagem redimensionada
            $image->save($resizedFullPath, self::QUALITY);
            
            return $resizedPath;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao redimensionar imagem: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtém dimensões do thumbnail baseado no tamanho
     */
    private function getThumbnailDimensions(string $size): array
    {
        return self::THUMBNAIL_SIZES[$size] ?? self::THUMBNAIL_SIZES[self::DEFAULT_SIZE];
    }
    
    /**
     * Gera caminho do thumbnail
     */
    private function generateThumbnailPath(string $originalPath, string $size): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        return $directory . '/thumbnails/' . $filename . '_' . $size . '.' . $extension;
    }
    
    /**
     * Gera caminho do thumbnail de fonte
     */
    private function generateFontThumbnailPath(string $fontPath): string
    {
        $pathInfo = pathinfo($fontPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        return $directory . '/thumbnails/' . $filename . '_preview.png';
    }
    
    /**
     * Gera caminho para imagem redimensionada
     */
    private function generateResizedPath(string $originalPath, int $width, int $height): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        return $directory . '/resized/' . $filename . '_' . $width . 'x' . $height . '.' . $extension;
    }
    
    /**
     * Cria thumbnail genérico para fontes
     */
    private function createGenericFontThumbnail(string $fontPath): ?string
    {
        try {
            // Cria uma imagem simples com ícone de fonte
            $width = 400;
            $height = 200;
            $image = Image::canvas($width, $height, '#f8f9fa');
            
            // Adiciona texto genérico
            $image->text('Font File', $width / 2, $height / 2 - 20, function ($font) {
                $font->size(24);
                $font->color('#6c757d');
                $font->align('center');
                $font->valign('middle');
            });
            
            // Adiciona nome do arquivo
            $fontName = pathinfo($fontPath, PATHINFO_FILENAME);
            $image->text($fontName, $width / 2, $height / 2 + 20, function ($font) {
                $font->size(16);
                $font->color('#495057');
                $font->align('center');
                $font->valign('middle');
            });
            
            // Gera caminho do thumbnail
            $thumbnailPath = $this->generateFontThumbnailPath($fontPath);
            $thumbnailFullPath = $this->fileStorageService->getFullPath($thumbnailPath);
            
            // Garante que o diretório existe
            $thumbnailDir = dirname($thumbnailPath);
            $this->fileStorageService->ensureDirectoryExists($thumbnailDir);
            
            // Salva o thumbnail
            $image->save($thumbnailFullPath, self::QUALITY);
            
            return $thumbnailPath;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao criar thumbnail genérico de fonte: ' . $e->getMessage());
            return null;
        }
    }
}