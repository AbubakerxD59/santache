<?php

namespace CodeIgniter3\Commands;

class VersionCommand extends BaseCommand
{
    protected $name = 'version';
    protected $description = '📋 Show application version info';
    
    public function execute()
    {
        $this->info("😎 Bruh CLI Tool v1.0.0");
        
        if ($this->initializeCodeIgniter()) {
            if (defined('CI_VERSION')) {
                $this->info("🔥 CodeIgniter Version: " . CI_VERSION);
            }
            if (defined('ENVIRONMENT')) {
                $this->info("🌍 Environment: " . ENVIRONMENT);
            }
        }
        
        $this->info("🐘 PHP Version: " . PHP_VERSION);
    }
}