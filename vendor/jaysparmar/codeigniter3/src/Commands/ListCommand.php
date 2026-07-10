<?php

namespace CodeIgniter3\Commands;

class ListCommand extends BaseCommand
{
    protected $name = 'list';
    protected $description = '📝 List all available commands';
    
    public function execute()
    {
        $this->info("📝 Available Commands, bruh:");
        $this->info("===========================");
        echo "\n";
        
        $commandRegistry = new CommandRegistry();
        $commands = $commandRegistry->getAllCommands();
        
        foreach ($commands as $command) {
            printf("%-20s %s\n", $this->colorize($command->getName(), 'green'), $command->getDescription());
        }
        echo "\n";
    }
}