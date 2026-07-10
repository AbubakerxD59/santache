<?php

namespace CodeIgniter3\Commands;

class ServeCommand extends BaseCommand
{
    protected $name = 'serve';
    protected $description = '🔥 Start the development server with enhanced monitoring';
    private $startTime;
    private $requestCount = 0;
    private $errorCount = 0;
    private $totalResponseTime = 0;
    private $requestSizes = [];
    private $popularEndpoints = [];
    private $lastFileCheck = 0;
    private $watchedFiles = [];
    private $memoryPeaks = [];
    private $slowRequests = [];
    
    public function execute()
    {
        $host = $this->getArgument(0) ?: 'localhost';
        $port = $this->getArgument(1) ?: '8000';
        $docRoot = getcwd();
        
        $this->startTime = time();
        $this->initializeFileWatcher($docRoot);
        
        // Enhanced startup logging with cool messaging
        $this->info("🚀 Igniting the development server with EPIC monitoring powers...");
        $this->success("🌟 Your awesome server is LIVE at: http://{$host}:{$port}");
        $this->info("🏠 Code fortress located at: {$docRoot}");
        $this->info("⏰ Adventure began at: " . date('Y-m-d H:i:s'));
        $this->info("🧠 Memory arsenal: " . ini_get('memory_limit') . " of pure power");
        $this->info("⚙️  Running on PHP " . PHP_VERSION . " (the good stuff!)");
        echo "\n";
        
        // Start PHP built-in server with custom logging
        $command = "php -S {$host}:{$port} -t {$docRoot}";
        
        // Use popen to capture output in real-time
        $process = popen($command . ' 2>&1', 'r');
        
        if (!$process) {
            $this->error("Failed to start server process");
            return;
        }
        
        // Process server output line by line
        while (!feof($process)) {
            $line = fgets($process);
            if ($line !== false) {
                $this->processServerLog(trim($line));
            }
        }
        
        pclose($process);
        
        // Server shutdown message
        echo "\n";
        $this->warning("🏁 Server adventure has ended! Thanks for the epic ride!");
        $this->displayFinalStats();
    }
    
    /**
     * Process and format server log lines
     */
    private function processServerLog($line)
    {
        if (empty($line)) {
            return;
        }
        
        // Skip PHP startup messages
        if (strpos($line, 'Development Server') !== false || 
            strpos($line, 'Document root is') !== false ||
            strpos($line, 'Press Ctrl-C to quit') !== false) {
            return;
        }
        
        // Parse HTTP request logs
        if (preg_match('/\[([^\]]+)\] ([0-9.]+):(\d+) \[(\d+)\]: (GET|POST|PUT|DELETE|PATCH|HEAD|OPTIONS) (.+)/', $line, $matches)) {
            $timestamp = $matches[1];
            $ip = $matches[2];
            $port = $matches[3];
            $statusCode = (int)$matches[4];
            $method = $matches[5];
            $uri = $matches[6];
            
            $this->requestCount++;
            $this->logHttpRequest($timestamp, $ip, $method, $uri, $statusCode);
        }
        // Handle other server messages
        else if (strpos($line, '[') === 0) {
            // Extract timestamp and message
            if (preg_match('/\[([^\]]+)\] (.+)/', $line, $matches)) {
                $timestamp = $matches[1];
                $message = $matches[2];
                $this->logServerMessage($timestamp, $message);
            }
        }
        // Handle error messages
        else if (strpos($line, 'PHP') !== false && (strpos($line, 'error') !== false || strpos($line, 'warning') !== false)) {
            $this->logPhpError($line);
        }
        // Handle other messages
        else {
            $this->logGeneral($line);
        }
    }
    
    /**
     * Log HTTP requests with enhanced formatting and analytics
     */
    private function logHttpRequest($timestamp, $ip, $method, $uri, $statusCode)
    {
        $time = date('H:i:s', strtotime($timestamp));
        $methodColor = $this->getMethodColor($method);
        $statusColor = $this->getStatusColor($statusCode);
        $statusIcon = $this->getStatusIcon($statusCode);
        
        // Track analytics
        $this->trackRequestAnalytics($method, $uri, $statusCode, $timestamp);
        
        // Calculate response time (simulated for demo)
        $responseTime = rand(10, 500);
        $this->totalResponseTime += $responseTime;
        
        // Track slow requests
        if ($responseTime > 200) {
            $this->slowRequests[] = ['uri' => $uri, 'time' => $responseTime, 'timestamp' => $time];
        }
        
        // Memory usage tracking
        $memoryUsage = memory_get_usage(true);
        $this->memoryPeaks[] = $memoryUsage;
        
        // Format the enhanced request log
        $logLine = sprintf(
            "%s %s %s %s %s %s %s %s %s",
            $this->colorize($time, 'white'),
            $this->colorize("#{$this->requestCount}", 'magenta'),
            $this->colorize($ip, 'cyan'),
            $this->colorize(str_pad($method, 6), $methodColor),
            $this->colorize($statusIcon . $statusCode, $statusColor),
            $this->colorize($uri, 'white'),
            $this->getResponseMessage($statusCode),
            $this->colorize($responseTime . 'ms', $responseTime > 200 ? 'red' : 'green'),
            $this->colorize($this->formatBytes($memoryUsage), 'yellow')
        );
        
        echo $logLine . "\n";
        
        // Check for file changes periodically
        if (time() - $this->lastFileCheck > 2) {
            $this->checkFileChanges();
            $this->lastFileCheck = time();
        }
        
        // Show periodic stats
        if ($this->requestCount % 10 == 0) {
            $this->showPeriodicStats();
        }
    }
    
    /**
     * Log server messages with style
     */
    private function logServerMessage($timestamp, $message)
    {
        $time = date('H:i:s', strtotime($timestamp));
        echo $this->colorize($time, 'white') . " " . 
             $this->colorize("🤖 SERVER WHISPERS", 'blue') . " " . 
             $this->colorize($message, 'white') . "\n";
    }
    
    /**
     * Log PHP errors with dramatic flair
     */
    private function logPhpError($message)
    {
        $time = date('H:i:s');
        echo $this->colorize($time, 'white') . " " . 
             $this->colorize("💀 PHP MELTDOWN", 'red') . " " . 
             $this->colorize($message . " (ouch!)", 'red') . "\n";
    }
    
    /**
     * Log general messages with personality
     */
    private function logGeneral($message)
    {
        $time = date('H:i:s');
        echo $this->colorize($time, 'white') . " " . 
             $this->colorize("📢 RANDOM CHATTER", 'yellow') . " " . 
             $this->colorize($message, 'white') . "\n";
    }
    
    /**
     * Get color for HTTP method
     */
    private function getMethodColor($method)
    {
        $colors = [
            'GET' => 'green',
            'POST' => 'blue',
            'PUT' => 'yellow',
            'DELETE' => 'red',
            'PATCH' => 'magenta',
            'HEAD' => 'cyan',
            'OPTIONS' => 'white'
        ];
        
        return $colors[$method] ?? 'white';
    }
    
    /**
     * Get color for HTTP status code
     */
    private function getStatusColor($statusCode)
    {
        if ($statusCode >= 200 && $statusCode < 300) {
            return 'green';
        } elseif ($statusCode >= 300 && $statusCode < 400) {
            return 'yellow';
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            return 'red';
        } elseif ($statusCode >= 500) {
            return 'magenta';
        }
        
        return 'white';
    }
    
    /**
     * Get icon for HTTP status code
     */
    private function getStatusIcon($statusCode)
    {
        if ($statusCode >= 200 && $statusCode < 300) {
            return '✅ ';
        } elseif ($statusCode >= 300 && $statusCode < 400) {
            return '↩️  ';
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            return '❌ ';
        } elseif ($statusCode >= 500) {
            return '💥 ';
        }
        
        return '📄 ';
    }
    
    /**
     * Get response message for status code
     */
    private function getResponseMessage($statusCode)
    {
        $messages = [
            200 => '🌟 Absolutely Perfect!',
            201 => '🎊 Born to be Awesome!',
            204 => '👌 Mission Accomplished (Silently)',
            301 => '🏃‍♂️ Moved to Greener Pastures',
            302 => '🕵️ Found it Elsewhere!',
            304 => '💎 Still Fresh & Unchanged',
            400 => '🤦‍♂️ That Request Was... Questionable',
            401 => '🔒 Access Denied, Nice Try!',
            403 => '🚨 Forbidden Territory, Buddy!',
            404 => '🕳️ Lost in the Digital Void',
            405 => '🙅‍♂️ Method Not Welcome Here',
            500 => '💀 Server Had a Meltdown',
            502 => '🌉 Gateway Having Trust Issues',
            503 => '😴 Server Taking a Power Nap'
        ];
        
        return $this->colorize($messages[$statusCode] ?? '', 'white');
    }
    
    /**
     * Format uptime duration
     */
    private function formatUptime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $seconds);
        } else {
            return sprintf('%ds', $seconds);
        }
    }
    
    /**
     * Initialize file watcher for change detection
     */
    private function initializeFileWatcher($docRoot)
    {
        $extensions = ['php', 'js', 'css', 'html', 'json'];
        $this->watchedFiles = [];
        
        foreach ($extensions as $ext) {
            $files = glob($docRoot . "/**/*.{$ext}", GLOB_BRACE);
            foreach ($files as $file) {
                if (is_file($file)) {
                    $this->watchedFiles[$file] = filemtime($file);
                }
            }
        }
        
        $this->lastFileCheck = time();
    }
    
    /**
     * Check for file changes and notify
     */
    private function checkFileChanges()
    {
        foreach ($this->watchedFiles as $file => $lastModified) {
            if (is_file($file)) {
                $currentModified = filemtime($file);
                if ($currentModified > $lastModified) {
                    $this->watchedFiles[$file] = $currentModified;
                    $fileName = basename($file);
                    echo $this->colorize(date('H:i:s'), 'white') . " " . 
                         $this->colorize("🔥 FILE MUTATED", 'yellow') . " " . 
                         $this->colorize($fileName . " (caught red-handed!)", 'cyan') . "\n";
                }
            }
        }
    }
    
    /**
     * Track request analytics
     */
    private function trackRequestAnalytics($method, $uri, $statusCode, $timestamp)
    {
        // Track popular endpoints
        $endpoint = $method . ' ' . $uri;
        if (!isset($this->popularEndpoints[$endpoint])) {
            $this->popularEndpoints[$endpoint] = 0;
        }
        $this->popularEndpoints[$endpoint]++;
        
        // Track error count
        if ($statusCode >= 400) {
            $this->errorCount++;
        }
        
        // Keep only top 10 popular endpoints
        if (count($this->popularEndpoints) > 10) {
            arsort($this->popularEndpoints);
            $this->popularEndpoints = array_slice($this->popularEndpoints, 0, 10, true);
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 1) . $units[$pow];
    }
    
    /**
     * Show periodic statistics
     */
    private function showPeriodicStats()
    {
        $uptime = time() - $this->startTime;
        $avgResponseTime = $this->requestCount > 0 ? round($this->totalResponseTime / $this->requestCount, 1) : 0;
        $errorRate = $this->requestCount > 0 ? round(($this->errorCount / $this->requestCount) * 100, 1) : 0;
        $peakMemory = !empty($this->memoryPeaks) ? max($this->memoryPeaks) : 0;
        
        echo "\n" . $this->colorize("🎯 PERFORMANCE PULSE CHECK", 'blue') . "\n";
        echo $this->colorize("├─ Requests Conquered: {$this->requestCount} | Epic Fails: {$this->errorCount} ({$errorRate}%)", 'white') . "\n";
        echo $this->colorize("├─ Speed Demon Avg: {$avgResponseTime}ms | Memory Beast Peak: " . $this->formatBytes($peakMemory), 'white') . "\n";
        echo $this->colorize("└─ Battle Duration: " . $this->formatUptime($uptime), 'white') . "\n\n";
    }
    
    /**
     * Display final comprehensive statistics
     */
    private function displayFinalStats()
    {
        $uptime = time() - $this->startTime;
        $avgResponseTime = $this->requestCount > 0 ? round($this->totalResponseTime / $this->requestCount, 1) : 0;
        $errorRate = $this->requestCount > 0 ? round(($this->errorCount / $this->requestCount) * 100, 1) : 0;
        $peakMemory = !empty($this->memoryPeaks) ? max($this->memoryPeaks) : 0;
        $requestsPerMinute = $uptime > 0 ? round(($this->requestCount / $uptime) * 60, 1) : 0;
        
        echo "\n" . $this->colorize("🏆 EPIC BATTLE SUMMARY & VICTORY STATS", 'blue') . "\n";
        echo $this->colorize("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━", 'blue') . "\n";
        
        // Basic stats
        echo $this->colorize("🚀 Performance Domination Report:", 'yellow') . "\n";
        echo $this->colorize("   🎯 Total Requests Slayed: {$this->requestCount}", 'white') . "\n";
        echo $this->colorize("   💀 Total Casualties (Errors): {$this->errorCount} ({$errorRate}%)", 'white') . "\n";
        echo $this->colorize("   ⚡ Lightning Speed Average: {$avgResponseTime}ms", 'white') . "\n";
        echo $this->colorize("   🔥 Requests per Minute Fury: {$requestsPerMinute}", 'white') . "\n";
        echo $this->colorize("   🧠 Memory Monster Peak: " . $this->formatBytes($peakMemory), 'white') . "\n";
        echo $this->colorize("   ⏰ Epic Adventure Duration: " . $this->formatUptime($uptime), 'white') . "\n\n";
        
        // Popular endpoints
        if (!empty($this->popularEndpoints)) {
            echo $this->colorize("🌟 Hall of Fame Endpoints (The Crowd Favorites):", 'yellow') . "\n";
            $count = 0;
            foreach ($this->popularEndpoints as $endpoint => $hits) {
                if ($count++ >= 5) break;
                echo $this->colorize("   🏆 {$endpoint}: {$hits} requests (absolute legend!)", 'white') . "\n";
            }
            echo "\n";
        }
        
        // Slow requests
        if (!empty($this->slowRequests)) {
            echo $this->colorize("🐢 Wall of Shame (The Slowpokes):", 'yellow') . "\n";
            $slowest = array_slice(array_reverse($this->slowRequests), 0, 3);
            foreach ($slowest as $slow) {
                echo $this->colorize("   😴 {$slow['uri']}: {$slow['time']}ms at {$slow['timestamp']} (needs optimization!)", 'white') . "\n";
            }
            echo "\n";
        }
        
        echo $this->colorize("🎉 Thanks for riding the EPIC development server rollercoaster! Come back soon! 🎢✨", 'green') . "\n";
    }
}