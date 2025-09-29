<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetTag;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ZipArchive;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Exibe o dashboard principal do Asset Library
     */
    public function index()
    {
        // Calcular estatísticas individuais
        $totalAssets = Asset::byUser(Auth::id())->count();
        $totalImages = Asset::byUser(Auth::id())->images()->count();
        $totalFonts = Asset::byUser(Auth::id())->fonts()->count();
        $totalStorage = $this->calculateStorageUsed();
        
        // Buscar assets recentes para exibir na página principal
        $recentAssets = Asset::byUser(Auth::id())->latest()->take(12)->get();
        
        // Dados adicionais para estatísticas
        $recentUploads = Asset::byUser(Auth::id())->latest()->take(5)->get();
        $popularTags = AssetTag::popular(10)->get();
        $downloadStats = DownloadLog::getDownloadStats(Auth::id());
        
        return view('assets.index', compact(
            'totalAssets', 
            'totalImages', 
            'totalFonts', 
            'totalStorage', 
            'recentAssets',
            'recentUploads',
            'popularTags',
            'downloadStats'
        ));
    }
    
    /**
     * Calcula o armazenamento usado pelo usuário
     */
    private function calculateStorageUsed(): string
    {
        $totalBytes = Asset::byUser(Auth::id())->sum('file_size');
        
        if ($totalBytes < 1024) {
            return $totalBytes . ' B';
        } elseif ($totalBytes < 1048576) {
            return round($totalBytes / 1024, 2) . ' KB';
        } elseif ($totalBytes < 1073741824) {
            return round($totalBytes / 1048576, 2) . ' MB';
        } else {
            return round($totalBytes / 1073741824, 2) . ' GB';
        }
    }
    
    /**
     * Exibe a seção de imagens
     */
    public function images(Request $request)
    {
        $query = Asset::byUser(Auth::id())->images()->with('tags');
        
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }
        
        if ($request->has('format') && $request->format) {
            $query->byFormat($request->format);
        }
        
        $images = $query->latest()->paginate(24);
        $formats = Asset::byUser(Auth::id())->images()->distinct()->pluck('format');
        
        return view('assets.images', compact('images', 'formats'));
    }
    
    /**
     * Exibe a seção de fontes
     */
    public function fonts(Request $request)
    {
        $query = Asset::byUser(Auth::id())->fonts()->with('tags');
        
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }
        
        if ($request->has('format') && $request->format) {
            $query->byFormat($request->format);
        }
        
        $fonts = $query->latest()->paginate(24);
        $formats = Asset::byUser(Auth::id())->fonts()->distinct()->pluck('format');
        
        return view('assets.fonts', compact('fonts', 'formats'));
    }
    
    /**
     * Exibe o centro de upload
     */
    public function upload()
    {
        return view('assets.upload');
    }
    
    /**
     * Exibe os dados de um asset específico
     */
    public function show(Asset $asset): JsonResponse
    {
        // Verificar se o asset pertence ao usuário autenticado
        if ($asset->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Asset não encontrado'
            ], 404);
        }
        
        // Carregar tags relacionadas
        $asset->load('tags');
        
        // Calcular dimensões para imagens
        $dimensions = null;
        if ($asset->type === 'image' && in_array($asset->format, ['png', 'jpg', 'jpeg'])) {
            try {
                $fullPath = Storage::disk('public')->path($asset->file_path);
                if (file_exists($fullPath)) {
                    $imageSize = getimagesize($fullPath);
                    if ($imageSize) {
                        $dimensions = [
                            'width' => $imageSize[0],
                            'height' => $imageSize[1]
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Erro ao obter dimensões da imagem', [
                    'asset_id' => $asset->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'asset' => [
                'id' => $asset->id,
                'name' => $asset->original_name,
                'stored_name' => $asset->stored_name,
                'type' => $asset->type,
                'format' => $asset->format,
                'file_size' => $asset->file_size,
                'file_size_formatted' => $this->formatFileSize($asset->file_size),
                'file_url' => $asset->file_url,
                'thumbnail_url' => $asset->thumbnail_url,
                'dimensions' => $dimensions,
                'tags' => $asset->tags->pluck('tag_name'),
                'created_at' => $asset->created_at->format('d/m/Y H:i'),
                'updated_at' => $asset->updated_at->format('d/m/Y H:i')
            ]
        ]);
    }
    
    /**
     * Formata o tamanho do arquivo
     */
    private function formatFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' KB';
        } elseif ($bytes < 1073741824) {
            return round($bytes / 1048576, 2) . ' MB';
        } else {
            return round($bytes / 1073741824, 2) . ' GB';
        }
    }
    
    /**
     * Processa o upload de múltiplos arquivos
     */
    public function store(Request $request): JsonResponse
    {
        \Log::info('AssetController::store - Iniciando upload', [
            'user_id' => Auth::id(),
            'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
            'tags' => $request->tags
        ]);
        
        $validator = Validator::make($request->all(), [
            'files' => 'required|array|min:1|max:20',
            'files.*' => [
                'required',
                'file',
                'max:10240',
                function ($attribute, $value, $fail) {
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg', 'otf', 'ttf', 'woff', 'woff2'];
                    $allowedMimeTypes = [
                        'image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml',
                        'font/ttf', 'font/otf', 'font/woff', 'font/woff2',
                        'application/font-woff', 'application/font-woff2',
                        'application/x-font-ttf', 'application/x-font-otf',
                        'application/font-sfnt', 'application/vnd.ms-fontobject'
                    ];
                    
                    $extension = strtolower($value->getClientOriginalExtension());
                    $mimeType = $value->getMimeType();
                    
                    if (!in_array($extension, $allowedExtensions) && !in_array($mimeType, $allowedMimeTypes)) {
                        $fail('O campo ' . $attribute . ' deve conter um arquivo do tipo: ' . implode(', ', $allowedExtensions) . '.');
                    }
                }
            ],
            'tags' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            \Log::error('AssetController::store - Erro de validação', [
                'errors' => $validator->errors()->toArray()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $uploadedAssets = [];
        $tags = $request->tags ? explode(',', $request->tags) : [];
        
        \Log::info('AssetController::store - Processando arquivos', [
            'files_count' => count($request->file('files')),
            'tags' => $tags
        ]);
        
        foreach ($request->file('files') as $index => $file) {
            try {
                \Log::info('AssetController::store - Processando arquivo', [
                    'index' => $index,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]);
                
                $asset = $this->processFileUpload($file, $tags);
                $uploadedAssets[] = $asset;
                
                \Log::info('AssetController::store - Arquivo processado com sucesso', [
                    'asset_id' => $asset->id,
                    'original_name' => $asset->original_name
                ]);
            } catch (\Exception $e) {
                \Log::error('AssetController::store - Erro ao processar arquivo', [
                    'file_name' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar arquivo: ' . $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        
        \Log::info('AssetController::store - Upload concluído', [
            'uploaded_count' => count($uploadedAssets)
        ]);
        
        return response()->json([
            'success' => true,
            'message' => count($uploadedAssets) . ' arquivo(s) enviado(s) com sucesso',
            'assets' => $uploadedAssets
        ]);
    }
    
    /**
     * Busca assets por termo
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'term' => 'required|string|min:2',
            'type' => 'nullable|in:image,font',
            'format' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $query = Asset::byUser(Auth::id())->search($request->term)->with('tags');
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->format) {
            $query->byFormat($request->format);
        }
        
        $assets = $query->latest()->limit($request->limit ?? 50)->get();
        
        return response()->json([
            'success' => true,
            'assets' => $assets,
            'count' => $assets->count()
        ]);
    }
    
    /**
     * Busca assets por lista de nomes
     */
    public function findByNames(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'names' => 'required|array|min:1|max:50',
            'names.*' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $assets = Asset::byUser(Auth::id())
            ->where(function ($query) use ($request) {
                foreach ($request->names as $name) {
                    $query->orWhere('original_name', 'LIKE', "%{$name}%")
                          ->orWhere('stored_name', 'LIKE', "%{$name}%");
                }
            })
            ->with('tags')
            ->get();
        
        return response()->json([
            'success' => true,
            'assets' => $assets,
            'found' => $assets->count(),
            'requested' => count($request->names)
        ]);
    }
    
    /**
     * Download individual de asset
     */
    public function download(Asset $asset)
    {
        if ($asset->user_id !== Auth::id()) {
            abort(403, 'Acesso negado');
        }
        
        if (!Storage::disk('public')->exists($asset->file_path)) {
            abort(404, 'Arquivo não encontrado');
        }
        
        // Log do download
        $asset->logDownload(Auth::id(), 'single', request()->ip());
        
        return Storage::disk('public')->download($asset->file_path, $asset->original_name);
    }
    
    /**
     * Download em lote (ZIP)
     */
    public function downloadBatch(Request $request)
    {
        $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'required|string|exists:assets,id'
        ]);

        $assetIds = $request->input('asset_ids');
        
        $assets = Asset::whereIn('id', $assetIds)
            ->where('user_id', auth()->id())
            ->get();

        if ($assets->isEmpty()) {
            return response()->json(['error' => 'Nenhum asset encontrado'], 404);
        }

        try {
            $zipPath = $this->createZipFile($assets);
            
            // Log downloads
            foreach ($assets as $asset) {
                DownloadLog::create([
                    'asset_id' => $asset->id,
                    'user_id' => auth()->id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar arquivo ZIP: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Deleta um asset
     */
    public function destroy(Asset $asset): JsonResponse
    {
        if ($asset->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado'
            ], 403);
        }
        
        try {
            $asset->delete(); // O model já cuida da remoção dos arquivos físicos
            
            return response()->json([
                'success' => true,
                'message' => 'Asset deletado com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Processa o upload de um arquivo individual
     */
    private function processFileUpload($file, array $tags = []): Asset
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        
        // Determina o tipo do arquivo
        $imageExtensions = ['png', 'jpg', 'jpeg', 'svg'];
        $fontExtensions = ['otf', 'ttf', 'woff', 'woff2'];
        
        if (in_array($extension, $imageExtensions)) {
            $type = 'image';
            $directory = 'assets/images';
        } elseif (in_array($extension, $fontExtensions)) {
            $type = 'font';
            $directory = 'assets/fonts';
        } else {
            throw new \Exception('Tipo de arquivo não suportado');
        }
        
        // Gera nome único para o arquivo
        $storedName = $this->generateUniqueFileName($originalName, $extension, $directory);
        
        // Salva o arquivo
        $filePath = $file->storeAs($directory, $storedName, 'public');
        
        // Cria thumbnail para imagens
        $thumbnailPath = null;
        if ($type === 'image' && in_array($extension, ['png', 'jpg', 'jpeg'])) {
            $thumbnailPath = $this->createThumbnail($filePath, $directory);
        }
        
        // Obtém dimensões para imagens
        $dimensions = null;
        if ($type === 'image' && in_array($extension, ['png', 'jpg', 'jpeg'])) {
            $fullPath = Storage::disk('public')->path($filePath);
            $imageSize = getimagesize($fullPath);
            if ($imageSize) {
                $dimensions = [
                    'width' => $imageSize[0],
                    'height' => $imageSize[1]
                ];
            }
        }
        
        // Cria o registro do asset
        $asset = Asset::create([
            'user_id' => Auth::id(),
            'original_name' => $originalName,
            'stored_name' => $storedName,
            'file_path' => $filePath,
            'thumbnail_path' => $thumbnailPath,
            'type' => $type,
            'format' => $extension,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'dimensions' => $dimensions
        ]);
        
        // Adiciona tags se fornecidas
        if (!empty($tags)) {
            $asset->addTags($tags);
        }
        
        return $asset->load('tags');
    }
    
    /**
     * Gera nome único para arquivo evitando duplicatas
     */
    private function generateUniqueFileName(string $originalName, string $extension, string $directory): string
    {
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = Str::slug($baseName);
        $fileName = $baseName . '.' . $extension;
        
        $counter = 1;
        while (Storage::disk('public')->exists($directory . '/' . $fileName)) {
            $fileName = $baseName . '_' . $counter . '.' . $extension;
            $counter++;
        }
        
        return $fileName;
    }
    
    /**
     * Cria thumbnail para imagem
     */
    private function createThumbnail(string $filePath, string $directory): ?string
    {
        try {
            $thumbnailDirectory = str_replace('assets/', 'assets/thumbnails/', $directory);
            $thumbnailName = 'thumb_' . basename($filePath);
            $thumbnailPath = $thumbnailDirectory . '/' . $thumbnailName;
            
            // Aqui você implementaria a lógica de criação de thumbnail
            // Por simplicidade, vamos apenas copiar o arquivo original
            // Em produção, use uma biblioteca como Intervention Image
            
            if (!Storage::disk('public')->exists($thumbnailDirectory)) {
                Storage::disk('public')->makeDirectory($thumbnailDirectory);
            }
            
            Storage::disk('public')->copy($filePath, $thumbnailPath);
            
            return $thumbnailPath;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Cria arquivo ZIP com os assets selecionados
     */
    private function createZipFile($assets): string
    {
        $zipFileName = 'assets_' . Str::random(10) . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Cria diretório temp se não existir
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Não foi possível criar o arquivo ZIP');
        }
        
        foreach ($assets as $asset) {
            $filePath = Storage::disk('public')->path($asset->file_path);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $asset->original_name);
            }
        }
        
        $zip->close();
        
        return $zipPath;
    }
}
