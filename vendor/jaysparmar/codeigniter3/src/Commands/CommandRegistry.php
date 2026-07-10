<?php

namespace CodeIgniter3\Commands;

class CommandRegistry
{
    private $commands = [];
    private $commandsPath;
    
    public function __construct()
    {
        $this->commandsPath = __DIR__;
        $this->discoverCommands();
    }
    
    /**
     * Discover all command classes in the Commands directory
     */
    private function discoverCommands()
    {
        $files = glob($this->commandsPath . '/*Command.php');
        
        foreach ($files as $file) {
            $className = basename($file, '.php');
            
            // Skip BaseCommand and CommandRegistry
            if ($className === 'BaseCommand' || $className === 'CommandRegistry') {
                continue;
            }
            
            $fullClassName = 'CodeIgniter3\\Commands\\' . $className;
            
            if (class_exists($fullClassName)) {
                try {
                    $command = new $fullClassName();
                    if ($command instanceof BaseCommand) {
                        $this->commands[$command->getName()] = $command;
                    }
                } catch (\Exception $e) {
                    // Skip commands that can't be instantiated
                    continue;
                }
            }
        }
    }
    
    /**
     * Get command by name
     */
    public function getCommand($name)
    {
        return isset($this->commands[$name]) ? $this->commands[$name] : null;
    }
    
    /**
     * Get all available commands
     */
    public function getAllCommands()
    {
        return $this->commands;
    }
    
    /**
     * Check if command exists
     */
    public function hasCommand($name)
    {
        return isset($this->commands[$name]);
    }
    
    /**
     * Get command names
     */
    public function getCommandNames()
    {
        return array_keys($this->commands);
    }
}