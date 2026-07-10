<?php

namespace CodeIgniter3\Commands;

class MakeSeedCommand extends BaseCommand
{
    protected $name = 'make:seed';
    protected $description = '🌱 Create a new seeder';
    
    public function execute()
    {
        $name = $this->getArgument(0);
        $table = $this->getArgument(1);
        
        if (!$name) {
            $this->error("💀 Yo bruh, seeder name is required!");
            $this->info("Usage: php bruh make:seed SeederName [table_name]");
            return;
        }
        
        $this->info("🌱 Chill bruh... Making that seeder: {$name}.php");
        
        // Generate the seeder
        if ($this->generateSeeder($name, $table)) {
            $this->success("✨ Seeder {$name} is ready to plant some data, bruh!");
        } else {
            $this->error("💀 Failed to create seeder {$name}!");
        }
    }
    
    /**
     * Generate seeder file from template
     * 
     * @param string $name
     * @param string $table
     * @return bool
     */
    private function generateSeeder($name, $table = null)
    {
        try {
            // Prepare seeder name and table name
            $seederName = ucfirst($name);
            if (!str_ends_with($seederName, 'Seeder')) {
                $seederName .= 'Seeder';
            }
            
            $tableName = $table ?: strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', str_replace('Seeder', '', $name))) . 's';
            
            // Load template
            $templatePath = dirname(__DIR__) . '/Templates/seeds/seed.php';
            if (!file_exists($templatePath)) {
                throw new Exception("Seeder template not found at: {$templatePath}");
            }
            
            $template = file_get_contents($templatePath);
            
            // Replace placeholders
            $replacements = [
                '{{SEED_NAME}}' => $seederName,
                '{{TABLE_NAME}}' => $tableName,
                '{{AUTHOR}}' => get_current_user(),
                '{{DATE}}' => date('Y-m-d H:i:s')
            ];
            
            $content = str_replace(array_keys($replacements), array_values($replacements), $template);
            
            // Determine output path
            $outputDir = getcwd() . '/application/controllers/seeders';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $outputPath = $outputDir . '/' . $seederName . '.php';
            
            // Check if file already exists
            if (file_exists($outputPath)) {
                $this->warning("⚠️  Seeder file already exists: {$outputPath}");
                return false;
            }
            
            // Write file
            if (file_put_contents($outputPath, $content) === false) {
                throw new Exception("Failed to write seeder file: {$outputPath}");
            }
            
            $this->info("📁 Seeder created at: {$outputPath}");
            $this->info("📊 Table: {$tableName}");
            $this->info("💡 Run: php index.php seeders/{$seederName}/run to execute this seeder");
            
            return true;
            
        } catch (Exception $e) {
            $this->error("Error generating seeder: " . $e->getMessage());
            return false;
        }
    }
}