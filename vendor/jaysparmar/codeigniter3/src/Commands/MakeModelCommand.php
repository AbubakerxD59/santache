<?php

namespace CodeIgniter3\Commands;

class MakeModelCommand extends BaseCommand
{
    protected $name = 'make:model';
    protected $description = '🚗 Create a new model';
    
    public function execute()
    {
        $name = $this->getArgument(0);
        $table = $this->getArgument(1);
        
        if (!$name) {
            $this->error("💀 Yo bruh, model name is required!");
            $this->info("Usage: php bruh make:model ModelName [table_name]");
            return;
        }
        
        $this->info("🚗 Chill bruh... Making that model: {$name}.php");
        
        // Generate the model
        if ($this->generateModel($name, $table)) {
            $this->success("✨ Model {$name} is ready to roll, bruh!");
        } else {
            $this->error("💀 Failed to create model {$name}!");
        }
    }
    
    /**
     * Generate model file from template
     * 
     * @param string $name
     * @param string $table
     * @return bool
     */
    private function generateModel($name, $table = null)
    {
        try {
            // Prepare model name and table name
            $modelName = ucfirst($name);
            $tableName = $table ?: strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name)) . 's';
            
            // Load template
            $templatePath = dirname(__DIR__) . '/Templates/models/model.php';
            if (!file_exists($templatePath)) {
                throw new Exception("Model template not found at: {$templatePath}");
            }
            
            $template = file_get_contents($templatePath);
            
            // Replace placeholders
            $replacements = [
                '{{MODEL_NAME}}' => $modelName,
                '{{TABLE_NAME}}' => $tableName,
                '{{AUTHOR}}' => get_current_user(),
                '{{DATE}}' => date('Y-m-d H:i:s')
            ];
            
            $content = str_replace(array_keys($replacements), array_values($replacements), $template);
            
            // Determine output path
            $outputDir = getcwd() . '/application/models';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $outputPath = $outputDir . '/' . $modelName . '_model.php';
            
            // Check if file already exists
            if (file_exists($outputPath)) {
                $this->warning("⚠️  Model file already exists: {$outputPath}");
                return false;
            }
            
            // Write file
            if (file_put_contents($outputPath, $content) === false) {
                throw new Exception("Failed to write model file: {$outputPath}");
            }
            
            $this->info("📁 Model created at: {$outputPath}");
            $this->info("📊 Table: {$tableName}");
            
            return true;
            
        } catch (Exception $e) {
            $this->error("Error generating model: " . $e->getMessage());
            return false;
        }
    }
}