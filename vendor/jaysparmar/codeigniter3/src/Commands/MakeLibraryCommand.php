<?php

namespace CodeIgniter3\Commands;

class MakeLibraryCommand extends BaseCommand
{
    protected $name = 'make:library';
    protected $description = '📚 Create a new library';
    
    public function execute()
    {
        $name = $this->getArgument(0);
        
        if (!$name) {
            $this->error("💀 Yo bruh, library name is required!");
            $this->info("Usage: php bruh make:library LibraryName");
            return;
        }
        
        $this->info("📚 Chill bruh... Making that library: {$name}.php");
        
        // Generate the library
        if ($this->generateLibrary($name)) {
            $this->success("✨ Library {$name} is ready to serve, bruh!");
        } else {
            $this->error("💀 Failed to create library {$name}!");
        }
    }
    
    /**
     * Generate library file from template
     * 
     * @param string $name
     * @return bool
     */
    private function generateLibrary($name)
    {
        try {
            // Prepare library name
            $libraryName = ucfirst($name);
            
            // Load template
            $templatePath = dirname(__DIR__) . '/Templates/libraries/library.php';
            if (!file_exists($templatePath)) {
                throw new Exception("Library template not found at: {$templatePath}");
            }
            
            $template = file_get_contents($templatePath);
            
            // Replace placeholders
            $replacements = [
                '{{LIBRARY_NAME}}' => $libraryName,
                '{{AUTHOR}}' => get_current_user(),
                '{{DATE}}' => date('Y-m-d H:i:s')
            ];
            
            $content = str_replace(array_keys($replacements), array_values($replacements), $template);
            
            // Determine output path
            $outputDir = getcwd() . '/application/libraries';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $outputPath = $outputDir . '/' . $libraryName . '.php';
            
            // Check if file already exists
            if (file_exists($outputPath)) {
                $this->warning("⚠️  Library file already exists: {$outputPath}");
                return false;
            }
            
            // Write file
            if (file_put_contents($outputPath, $content) === false) {
                throw new Exception("Failed to write library file: {$outputPath}");
            }
            
            $this->info("📁 Library created at: {$outputPath}");
            $this->info("💡 Load with: \$this->load->library('{$name}')");
            $this->info("🔧 Configure in: application/config/autoload.php");
            
            return true;
            
        } catch (Exception $e) {
            $this->error("Error generating library: " . $e->getMessage());
            return false;
        }
    }
}