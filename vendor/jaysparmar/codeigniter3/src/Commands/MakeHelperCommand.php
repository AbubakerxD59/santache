<?php

namespace CodeIgniter3\Commands;

class MakeHelperCommand extends BaseCommand
{
    protected $name = 'make:helper';
    protected $description = '🛠️ Create a new helper';
    
    public function execute()
    {
        $name = $this->getArgument(0);
        
        if (!$name) {
            $this->error("💀 Yo bruh, helper name is required!");
            $this->info("Usage: php bruh make:helper helper_name");
            return;
        }
        
        $this->info("🛠️ Chill bruh... Making that helper: {$name}_helper.php");
        
        // Generate the helper
        if ($this->generateHelper($name)) {
            $this->success("✨ Helper {$name} is ready to help, bruh!");
        } else {
            $this->error("💀 Failed to create helper {$name}!");
        }
    }
    
    /**
     * Generate helper file from template
     * 
     * @param string $name
     * @return bool
     */
    private function generateHelper($name)
    {
        try {
            // Prepare helper name and function prefix
            $helperName = ucfirst($name);
            $helperNameLower = strtolower($name);
            $functionPrefix = strtolower($name);
            
            // Load template
            $templatePath = dirname(__DIR__) . '/Templates/helpers/helper.php';
            if (!file_exists($templatePath)) {
                throw new Exception("Helper template not found at: {$templatePath}");
            }
            
            $template = file_get_contents($templatePath);
            
            // Replace placeholders
            $replacements = [
                '{{HELPER_NAME}}' => $helperName,
                '{{HELPER_FUNCTION_PREFIX}}' => $functionPrefix,
                '{{AUTHOR}}' => get_current_user(),
                '{{DATE}}' => date('Y-m-d H:i:s')
            ];
            
            $content = str_replace(array_keys($replacements), array_values($replacements), $template);
            
            // Determine output path
            $outputDir = getcwd() . '/application/helpers';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $filename = $helperNameLower . '_helper.php';
            $outputPath = $outputDir . '/' . $filename;
            
            // Check if file already exists
            if (file_exists($outputPath)) {
                $this->warning("⚠️  Helper file already exists: {$outputPath}");
                return false;
            }
            
            // Write file
            if (file_put_contents($outputPath, $content) === false) {
                throw new Exception("Failed to write helper file: {$outputPath}");
            }
            
            $this->info("📁 Helper created at: {$outputPath}");
            $this->info("💡 Load with: \$this->load->helper('{$helperNameLower}')");
            $this->info("🔧 Configure in: application/config/autoload.php");
            $this->info("🎯 Function prefix: {$functionPrefix}_");
            
            // Show available functions
            $this->info("📋 Available functions:");
            $this->info("   - {$functionPrefix}_format()");
            $this->info("   - {$functionPrefix}_validate()");
            $this->info("   - {$functionPrefix}_generate()");
            $this->info("   - {$functionPrefix}_sanitize()");
            $this->info("   - {$functionPrefix}_time_ago()");
            $this->info("   - {$functionPrefix}_file_size()");
            $this->info("   - {$functionPrefix}_truncate()");
            
            return true;
            
        } catch (Exception $e) {
            $this->error("Error generating helper: " . $e->getMessage());
            return false;
        }
    }
}