<?php

namespace CodeIgniter3\Commands;

class MakeMigrationCommand extends BaseCommand
{
    protected $name = 'make:migration';
    protected $description = '📦 Create a new migration file';
    
    public function execute()
    {
        $name = $this->getArgument(0);
        $table = $this->getArgument(1);
        
        if (!$name) {
            $this->error("💀 Yo bruh, migration name is required!");
            $this->info("Usage: php bruh make:migration migration_name [table_name]");
            return;
        }
        
        $this->info("📦 Chill bruh... Making that migration: {$name}.php");
        
        // Generate the migration
        if ($this->generateMigration($name, $table)) {
            $this->success("✨ Migration {$name} is ready to migrate, bruh!");
        } else {
            $this->error("💀 Failed to create migration {$name}!");
        }
    }
    
    /**
     * Generate migration file from template
     * 
     * @param string $name
     * @param string $table
     * @return bool
     */
    private function generateMigration($name, $table = null)
    {
        try {
            // Prepare migration name and table name
            $migrationName = ucfirst(str_replace('_', ' ', $name));
            $migrationClass = ucfirst(str_replace(' ', '_', $migrationName));
            $tableName = $table ?: $this->extractTableFromName($name);
            
            // Generate timestamp for migration filename
            $timestamp = date('YmdHis');
            
            // Load template
            $templatePath = dirname(__DIR__) . '/Templates/migrations/migration.php';
            if (!file_exists($templatePath)) {
                throw new Exception("Migration template not found at: {$templatePath}");
            }
            
            $template = file_get_contents($templatePath);
            
            // Replace placeholders
            $replacements = [
                '{{MIGRATION_NAME}}' => $migrationName,
                '{{MIGRATION_CLASS}}' => $migrationClass,
                '{{TABLE_NAME}}' => $tableName,
                '{{AUTHOR}}' => get_current_user(),
                '{{DATE}}' => date('Y-m-d H:i:s')
            ];
            
            $content = str_replace(array_keys($replacements), array_values($replacements), $template);
            
            // Determine output path
            $outputDir = getcwd() . '/application/migrations';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $filename = $timestamp . '_' . strtolower(str_replace(' ', '_', $name)) . '.php';
            $outputPath = $outputDir . '/' . $filename;
            
            // Check if similar migration already exists
            $existingFiles = glob($outputDir . '/*_' . strtolower(str_replace(' ', '_', $name)) . '.php');
            if (!empty($existingFiles)) {
                $this->warning("⚠️  Similar migration already exists: " . basename($existingFiles[0]));
                return false;
            }
            
            // Write file
            if (file_put_contents($outputPath, $content) === false) {
                throw new Exception("Failed to write migration file: {$outputPath}");
            }
            
            $this->info("📁 Migration created at: {$outputPath}");
            $this->info("📊 Table: {$tableName}");
            $this->info("🕐 Timestamp: {$timestamp}");
            $this->info("💡 Run: php bruh migrate to apply this migration");
            
            return true;
            
        } catch (Exception $e) {
            $this->error("Error generating migration: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Extract table name from migration name
     * 
     * @param string $name
     * @return string
     */
    private function extractTableFromName($name)
    {
        // Common patterns for migration names
        $patterns = [
            '/^create_(.+)_table$/' => '$1',
            '/^add_(.+)_to_(.+)$/' => '$2',
            '/^remove_(.+)_from_(.+)$/' => '$2',
            '/^modify_(.+)_table$/' => '$1',
            '/^drop_(.+)_table$/' => '$1'
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            if (preg_match($pattern, $name, $matches)) {
                return $matches[1];
            }
        }
        
        // Default: use the migration name as table name
        return strtolower(str_replace(' ', '_', $name));
    }
}