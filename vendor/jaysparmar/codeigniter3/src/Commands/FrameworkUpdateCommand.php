<?php

namespace CodeIgniter3\Commands;

class FrameworkUpdateCommand extends BaseCommand
{
    protected $name = 'framework:update';
    protected $description = '🚀 Update framework to modern jaysparmar/codeigniter3 package';
    
    public function execute()
    {
        $this->info("🚀 Yo bruh, let's upgrade your CodeIgniter 3 to the modern approach!");
        $this->info("=====================================");
        echo "\n";
        
        // Step 1: Pre-flight checks
        if (!$this->performPreflightChecks()) {
            return;
        }
        
        // Step 2: Create backup
        $backupPath = $this->createBackup();
        if (!$backupPath) {
            return;
        }
        
        // Step 3: Update composer.json
        if (!$this->updateComposerJson()) {
            $this->error("💀 Failed to update composer.json, bruh!");
            return;
        }
        
        // Step 4: Install dependencies
        if (!$this->installDependencies()) {
            $this->error("💀 Failed to install dependencies, bruh!");
            return;
        }
        
        // Step 5: Create .env file
        if (!$this->createEnvFile()) {
            $this->error("💀 Failed to create .env file, bruh!");
            return;
        }
        
        // Step 6: Update index.php
        if (!$this->updateIndexPhp()) {
            $this->error("💀 Failed to update index.php, bruh!");
            return;
        }
        
        // Step 7: Update constants.php
        if (!$this->updateConstantsPhp()) {
            $this->error("💀 Failed to update constants.php, bruh!");
            return;
        }
        
        // Step 8: Migrate system directory
        if (!$this->migrateSystemDirectory()) {
            $this->error("💀 Failed to migrate system directory, bruh!");
            return;
        }
        
        // Step 9: Final verification
        if (!$this->verifyMigration()) {
            $this->error("💀 Migration verification failed, bruh!");
            return;
        }
        
        echo "\n";
        $this->success("🎉 Framework update completed successfully, bruh!");
        $this->info("✨ Your CodeIgniter 3 is now running on the modern jaysparmar/codeigniter3 package!");
        $this->info("📦 Backup created at: {$backupPath}");
        echo "\n";
    }
    
    /**
     * Perform pre-flight checks before migration
     */
    private function performPreflightChecks()
    {
        $this->info("🔍 Running pre-flight checks...");
        
        // Check if we're in a CI3 project
        if (!file_exists('index.php')) {
            $this->error("💀 No index.php found. Are you in a CodeIgniter project directory?");
            return false;
        }
        
        if (!is_dir('application')) {
            $this->error("💀 No application directory found. This doesn't look like a CI3 project, bruh!");
            return false;
        }
        
        if (!is_dir('system')) {
            $this->error("💀 No system directory found. Looks like you're already using the modern approach, bruh!");
            return false;
        }
        
        // Check write permissions
        if (!is_writable('.')) {
            $this->error("💀 Current directory is not writable. Check permissions, bruh!");
            return false;
        }
        
        // Check if composer is available
        exec('composer --version 2>&1', $output, $returnCode);
        if ($returnCode !== 0) {
            $this->error("💀 Composer not found. Install Composer first, bruh!");
            return false;
        }
        
        $this->success("✅ Pre-flight checks passed!");
        return true;
    }
    
    /**
     * Create backup of critical files
     */
    private function createBackup()
    {
        $this->info("📦 Creating backup of your current setup...");
        
        $timestamp = date('Ymd_His');
        $backupPath = "ci3_migration_backup_{$timestamp}.tar.gz";
        
        $filesToBackup = [
            'index.php',
            'system/',
            'application/config/constants.php'
        ];
        
        $existingFiles = [];
        foreach ($filesToBackup as $file) {
            if (file_exists($file)) {
                $existingFiles[] = $file;
            }
        }
        
        if (empty($existingFiles)) {
            $this->error("💀 No files to backup found, bruh!");
            return false;
        }
        
        $command = 'tar -czf ' . escapeshellarg($backupPath) . ' ' . implode(' ', array_map('escapeshellarg', $existingFiles));
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->error("💀 Failed to create backup, bruh!");
            return false;
        }
        
        $this->success("✅ Backup created: {$backupPath}");
        return $backupPath;
    }
    
    /**
     * Update composer.json with modern CI3 package
     */
    private function updateComposerJson()
    {
        $this->info("📝 Updating composer.json...");
        
        $composerData = [];
        if (file_exists('composer.json')) {
            $composerData = json_decode(file_get_contents('composer.json'), true);
            if (!$composerData) {
                $composerData = [];
            }
        }
        
        // Add jaysparmar/codeigniter3 dependency
        if (!isset($composerData['require'])) {
            $composerData['require'] = [];
        }
        $composerData['require']['jaysparmar/codeigniter3'] = '*';
        
        // Add minimum stability for development
        $composerData['minimum-stability'] = 'dev';
        $composerData['prefer-stable'] = true;
        
        // Add repository for development (this should be updated to use packagist in production)
        if (!isset($composerData['repositories'])) {
            $composerData['repositories'] = [];
        }
        
        // Check if repository already exists
        $repoExists = false;
        foreach ($composerData['repositories'] as $repo) {
            if (isset($repo['url']) && strpos($repo['url'], 'codeigniter3') !== false) {
                $repoExists = true;
                break;
            }
        }
        
        if (!$repoExists) {
            $composerData['repositories'][] = [
                'type' => 'path',
                'url' => '/Users/jayparmar/Desktop/codeigniter3'
            ];
        }
        
        $jsonContent = json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (file_put_contents('composer.json', $jsonContent) === false) {
            return false;
        }
        
        $this->success("✅ composer.json updated!");
        return true;
    }
    
    /**
     * Install composer dependencies
     */
    private function installDependencies()
    {
        $this->info("📦 Installing dependencies...");
        
        exec('composer install 2>&1', $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->error("💀 Composer install failed:");
            foreach ($output as $line) {
                echo "   " . $line . "\n";
            }
            return false;
        }
        
        $this->success("✅ Dependencies installed!");
        return true;
    }
    
    /**
     * Create .env file
     */
    private function createEnvFile()
    {
        $this->info("🌍 Creating .env file...");
        
        if (file_exists('.env')) {
            $this->info("   .env file already exists, skipping...");
            return true;
        }
        
        $envContent = "APP_NAME=CodeIgniter3\nCI_ENV=production\n";
        
        if (file_put_contents('.env', $envContent) === false) {
            return false;
        }
        
        $this->success("✅ .env file created!");
        return true;
    }
    
    /**
     * Update index.php to modern approach
     */
    private function updateIndexPhp()
    {
        $this->info("🔄 Updating index.php to modern approach...");
        
        $modernIndexContent = '<?php
const CI_VERSION = \'3.1.13\';
$autoload = __DIR__ . \'/vendor/autoload.php\';

if (file_exists($autoload)) {
    require_once $autoload;

    if (class_exists(\'\\CodeIgniter3\\Bootstrap\')) {
        \\CodeIgniter3\\Bootstrap::init();
    } else {
        echo \'Bootstrap class not found in autoload.\';
        exit;
    }
}

';
        
        if (file_put_contents('index.php', $modernIndexContent) === false) {
            return false;
        }
        
        $this->success("✅ index.php updated to modern approach!");
        return true;
    }
    
    /**
     * Update constants.php for CLI compatibility
     */
    private function updateConstantsPhp()
    {
        $this->info("⚙️ Updating constants.php for CLI compatibility...");
        
        $constantsPath = 'application/config/constants.php';
        if (!file_exists($constantsPath)) {
            $this->warning("⚠️ constants.php not found, skipping...");
            return true;
        }
        
        $content = file_get_contents($constantsPath);
        
        // Fix APP_URL definition for CLI compatibility
        $oldPattern = '/define\(\'APP_URL\',.*?\);/s';
        $newDefinition = 'define(\'APP_URL\', (isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] === \'on\' ? "https" : "http") . "://" . (isset($_SERVER[\'HTTP_HOST\']) ? $_SERVER[\'HTTP_HOST\'] : \'localhost\') . str_replace(basename(isset($_SERVER[\'SCRIPT_NAME\']) ? $_SERVER[\'SCRIPT_NAME\'] : \'index.php\'), "", isset($_SERVER[\'SCRIPT_NAME\']) ? $_SERVER[\'SCRIPT_NAME\'] : \'/\'));';
        
        $updatedContent = preg_replace($oldPattern, $newDefinition, $content);
        
        if ($updatedContent && file_put_contents($constantsPath, $updatedContent) !== false) {
            $this->success("✅ constants.php updated for CLI compatibility!");
            return true;
        }
        
        $this->warning("⚠️ Could not update constants.php, but continuing...");
        return true;
    }
    
    /**
     * Migrate system directory to vendor package
     */
    private function migrateSystemDirectory()
    {
        $this->info("🚚 Migrating system directory to vendor package...");
        
        $vendorSystemPath = 'vendor/jaysparmar/codeigniter3/src/System';
        
        // Create vendor system directory if it doesn't exist
        if (!is_dir($vendorSystemPath)) {
            if (!mkdir($vendorSystemPath, 0755, true)) {
                $this->error("💀 Failed to create vendor system directory!");
                return false;
            }
        }
        
        // Copy system files to vendor directory
        exec("cp -r system/* {$vendorSystemPath}/", $output, $returnCode);
        if ($returnCode !== 0) {
            $this->error("💀 Failed to copy system files to vendor directory!");
            return false;
        }
        
        $this->success("✅ System files copied to vendor directory!");
        
        // Remove old system directory
        $this->info("🗑️ Removing old system directory...");
        exec("rm -rf system/", $output, $returnCode);
        if ($returnCode !== 0) {
            $this->error("💀 Failed to remove old system directory!");
            return false;
        }
        
        $this->success("✅ Old system directory removed!");
        return true;
    }
    
    /**
     * Verify migration was successful
     */
    private function verifyMigration()
    {
        $this->info("🔍 Verifying migration...");
        
        // Check if vendor autoload exists
        if (!file_exists('vendor/autoload.php')) {
            $this->error("💀 Vendor autoload not found!");
            return false;
        }
        
        // Check if Bootstrap class is available
        require_once 'vendor/autoload.php';
        if (!class_exists('\\CodeIgniter3\\Bootstrap')) {
            $this->error("💀 Bootstrap class not found!");
            return false;
        }
        
        // Check if system directory is gone
        if (is_dir('system')) {
            $this->error("💀 Old system directory still exists!");
            return false;
        }
        
        // Check if vendor system directory exists
        if (!is_dir('vendor/jaysparmar/codeigniter3/src/System')) {
            $this->error("💀 Vendor system directory not found!");
            return false;
        }
        
        $this->success("✅ Migration verification passed!");
        return true;
    }
}