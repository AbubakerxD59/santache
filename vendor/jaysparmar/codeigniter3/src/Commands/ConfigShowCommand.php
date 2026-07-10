<?php

namespace CodeIgniter3\Commands;

class ConfigShowCommand extends BaseCommand
{
    protected $name = 'config:show';
    protected $description = '⚙️ Show current configuration';
    
    public function execute()
    {
        $this->info("⚙️ Current Configuration:");
        $this->info("========================");
        echo "\n";
        
        if ($this->initializeCodeIgniter()) {
            if (defined('ENVIRONMENT')) {
                echo "Environment: " . $this->colorize(ENVIRONMENT, 'yellow') . "\n";
            }
            if (defined('BASEPATH')) {
                echo "Base Path: " . BASEPATH . "\n";
            }
            if (defined('APPPATH')) {
                echo "Application Path: " . APPPATH . "\n";
            }
            if (defined('VIEWPATH')) {
                echo "View Path: " . VIEWPATH . "\n";
            }
        }
        
        echo "PHP Version: " . PHP_VERSION . "\n";
        echo "Current Directory: " . getcwd() . "\n";
        echo "\n";
    }
}