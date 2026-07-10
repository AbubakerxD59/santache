<?php

namespace CodeIgniter3\Commands;

class HelpCommand extends BaseCommand
{
    protected $name = 'help';
    protected $description = '💡 Show this help message, bruh';
    
    public function execute()
    {
        $this->info("😎 Bruh CLI Tool");
        $this->info("================");
        echo "\n";
        $this->info("Usage:");
        echo "  php bruh [command] [options]\n\n";
        
        $this->info("Available commands, bruh:");
        
        // Get all available commands from the command registry
        $commandRegistry = new CommandRegistry();
        $commands = $commandRegistry->getAllCommands();
        
        foreach ($commands as $command) {
            printf("  %-20s %s\n", $this->colorize($command->getName(), 'green'), $command->getDescription());
        }
        echo "\n";
        
        $this->info("Options:");
        echo "  -h, --help       Show this help message, bruh\n";
        echo "  -v, --version    Show version information\n";
        echo "\n";
    }
}