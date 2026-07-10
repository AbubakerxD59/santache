<?php

namespace CodeIgniter3\Commands;

abstract class BaseCommand
{
    protected $args = [];
    protected $name = '';
    protected $description = '';
    
    public function __construct($args = [])
    {
        $this->args = $args;
    }
    
    /**
     * Set command arguments
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }
    
    /**
     * Execute the command
     */
    abstract public function execute();
    
    /**
     * Get command name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Get command description
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Initialize CodeIgniter for CLI usage
     */
    protected function initializeCodeIgniter()
    {
        try {
            if (class_exists('\CodeIgniter3\Bootstrap')) {
                \CodeIgniter3\Bootstrap::initCLI();
                return true;
            } else {
                $this->error('Bootstrap class not found. Make sure CodeIgniter is properly installed.');
                return false;
            }
        } catch (\Exception $e) {
            $this->error('Failed to initialize CodeIgniter: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get command argument by index
     */
    protected function getArgument($index)
    {
        return isset($this->args[$index]) ? $this->args[$index] : null;
    }
    
    /**
     * Output colored text
     */
    protected function colorize($text, $color)
    {
        $colors = [
            'red' => "\033[31m",
            'green' => "\033[32m",
            'yellow' => "\033[33m",
            'blue' => "\033[34m",
            'magenta' => "\033[35m",
            'cyan' => "\033[36m",
            'white' => "\033[37m",
            'reset' => "\033[0m"
        ];
        
        return isset($colors[$color]) ? $colors[$color] . $text . $colors['reset'] : $text;
    }
    
    /**
     * Output info message with cool styling
     */
    protected function info($message)
    {
        echo $this->colorize("ℹ️  " . $message, 'cyan') . "\n";
    }

    /**
     * Output success message with celebration
     */
    protected function success($message)
    {
        echo $this->colorize("🎉 " . $message, 'green') . "\n";
    }

    /**
     * Output warning message with attention-grabbing style
     */
    protected function warning($message)
    {
        echo $this->colorize("⚡ " . $message, 'yellow') . "\n";
    }

    /**
     * Output error message with dramatic flair
     */
    protected function error($message)
    {
        echo $this->colorize("💥 " . $message, 'red') . "\n";
    }
}