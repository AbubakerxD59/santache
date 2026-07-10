<?php

namespace CodeIgniter3\Commands;

class MakeControllerCommand extends BaseCommand
{
    protected $name = 'make:controller';
    protected $description = '🎮 Create a new controller';
    
    public function execute()
    {
        $name = $this->getArgument(0);
        $model = $this->getArgument(1);
        
        if (!$name) {
            $this->error("💀 Yo bruh, controller name is required!");
            $this->info("Usage: php bruh make:controller ControllerName [ModelName]");
            return;
        }
        
        $this->info("🎮 Chill bruh... Making that controller: {$name}.php");
        
        // Generate the controller
        if ($this->generateController($name, $model)) {
            $this->success("✨ Controller {$name} is locked and loaded, bruh!");
        } else {
            $this->error("💀 Failed to create controller {$name}!");
        }
    }
    
    /**
     * Generate controller file from template
     * 
     * @param string $name
     * @param string $model
     * @return bool
     */
    private function generateController($name, $model = null)
    {
        try {
            // Prepare controller name and related names
            $controllerName = ucfirst($name);
            $controllerNameLower = strtolower($name);
            $modelName = $model ?: $name;
            $viewPath = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
            
            // Load template
            $templatePath = dirname(__DIR__) . '/Templates/controllers/controller.php';
            if (!file_exists($templatePath)) {
                throw new Exception("Controller template not found at: {$templatePath}");
            }
            
            $template = file_get_contents($templatePath);
            
            // Replace placeholders
            $replacements = [
                '{{CONTROLLER_NAME}}' => $controllerName,
                '{{CONTROLLER_NAME_LOWER}}' => $controllerNameLower,
                '{{MODEL_NAME}}' => ucfirst($modelName),
                '{{VIEW_PATH}}' => $viewPath,
                '{{AUTHOR}}' => get_current_user(),
                '{{DATE}}' => date('Y-m-d H:i:s')
            ];
            
            $content = str_replace(array_keys($replacements), array_values($replacements), $template);
            
            // Determine output path
            $outputDir = getcwd() . '/application/controllers';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $outputPath = $outputDir . '/' . $controllerName . '.php';
            
            // Check if file already exists
            if (file_exists($outputPath)) {
                $this->warning("⚠️  Controller file already exists: {$outputPath}");
                return false;
            }
            
            // Write file
            if (file_put_contents($outputPath, $content) === false) {
                throw new Exception("Failed to write controller file: {$outputPath}");
            }
            
            $this->info("📁 Controller created at: {$outputPath}");
            $this->info("🎯 Model: {$modelName}");
            $this->info("👁️  Views path: application/views/{$viewPath}/");
            
            // Create views directory
            $viewsDir = getcwd() . '/application/views/' . $viewPath;
            if (!is_dir($viewsDir)) {
                mkdir($viewsDir, 0755, true);
                $this->info("📂 Created views directory: {$viewsDir}");
            }
            
            return true;
            
        } catch (Exception $e) {
            $this->error("Error generating controller: " . $e->getMessage());
            return false;
        }
    }
}