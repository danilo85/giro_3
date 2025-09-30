<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

class ListFonts extends Command
{
    protected $signature = 'fonts:list';
    protected $description = 'List all registered fonts and check if files exist';

    public function handle()
    {
        $fonts = Asset::where('type', 'font')->get(['original_name', 'file_path']);
        
        $this->info('Registered fonts:');
        $this->line('');
        
        foreach ($fonts as $font) {
            $exists = Storage::disk('public')->exists($font->file_path) ? '✓' : '✗';
            $this->line("{$exists} {$font->original_name} -> {$font->file_path}");
        }
        
        $this->line('');
        $this->info('Total fonts: ' . $fonts->count());
        
        return 0;
    }
}