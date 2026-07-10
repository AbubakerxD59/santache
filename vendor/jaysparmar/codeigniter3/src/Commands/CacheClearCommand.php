<?php

namespace CodeIgniter3\Commands;

class CacheClearCommand extends BaseCommand
{
    protected $name = 'cache:clear';
    protected $description = '🧹 Clear application cache';
    
    public function execute()
    {
        $this->info("🧹 Yo bruh, cleaning up that cache...");
        
        $cacheDir = getcwd() . '/application/cache';
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '/*');
            $cleared = 0;
            
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'index.html' && basename($file) !== '.htaccess') {
                    if (unlink($file)) {
                        $cleared++;
                    }
                }
            }
            
            $this->success("✨ Cache is squeaky clean, bruh! Removed {$cleared} files.");
        } else {
            $this->warning("🤔 Cache directory not found, bruh.");
        }
    }
}