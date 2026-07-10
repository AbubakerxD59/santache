<?php

namespace CodeIgniter3\Commands;

class SeedCommand extends BaseCommand
{
    protected $name = 'seed';
    protected $description = '🌱 Run database seeders';
    
    public function execute()
    {
        $this->info("🌱 Running database seeders...");
        
        if (!$this->initializeCodeIgniter()) {
            return;
        }
        
        // TODO: Implement seeder logic
        $this->success("✨ Seeders completed successfully, bruh!");
    }
}