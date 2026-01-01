<?php

namespace App\Services\Olt;

use Exception;

class TelnetService
{
    private $socket;
    private $host;
    private $port;
    private $timeout;
    private $streamTimeout;
    private $buffer = '';
    private $globalBuffer = '';
    private $prompt;
    private $errno;
    private $errstr;

    public function __construct($host = '127.0.0.1', $port = 23, $timeout = 10, $streamTimeout = 2)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->streamTimeout = $streamTimeout;
    }

    /**
     * Connect to the Telnet server
     */
    public function connect()
    {
        $this->socket = @fsockopen($this->host, $this->port, $this->errno, $this->errstr, $this->timeout);

        if (!$this->socket) {
            throw new Exception("Telnet connection failed: {$this->errstr} ({$this->errno})");
        }

        stream_set_timeout($this->socket, $this->streamTimeout);
        return true;
    }

    /**
     * Login to the OLT
     */
    public function login($username, $password, $prompt = '>')
    {
        $this->prompt = $prompt;
        
        try {
            \Illuminate\Support\Facades\Log::info("Telnet: Waiting for login prompt...");
            $buffer = $this->waitPrompt(['login:', 'Login:', 'Username:', 'username:', 'User:', 'user:']);
            \Illuminate\Support\Facades\Log::info("Telnet: Received login buffer: " . trim($buffer));
            
            $this->write($username);
            \Illuminate\Support\Facades\Log::info("Telnet: Sent username");
            
            $buffer = $this->waitPrompt(['Password:', 'password:']);
            \Illuminate\Support\Facades\Log::info("Telnet: Received password buffer: " . trim($buffer));

            $this->write($password);
            \Illuminate\Support\Facades\Log::info("Telnet: Sent password");
            
            $buffer = $this->waitPrompt([$this->prompt, '#', '$', '>']);
            \Illuminate\Support\Facades\Log::info("Telnet: Login successful, buffer: " . trim($buffer));

            return true;
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error("Telnet Login Failed: " . $e->getMessage());
            $this->disconnect();
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a command and return the output
     */
    public function exec($command, $waitForPrompt = true)
    {
        $this->write($command);
        
        if ($waitForPrompt) {
            return $this->waitPrompt([$this->prompt, '#', '>']);
        }
        
        return $this->read();
    }

    /**
     * Write data to the socket
     */
    public function write($data)
    {
        if (!$this->socket) {
            throw new Exception("Not connected");
        }

        // Add newline if not present
        if (substr($data, -1) !== "\n") {
            $data .= "\n";
        }

        fwrite($this->socket, $data);
        usleep(100000); // Small delay
    }

    /**
     * Read data from the socket
     */
    public function read()
    {
        if (!$this->socket) {
            throw new Exception("Not connected");
        }

        $buffer = '';
        while (!feof($this->socket)) {
            $chunk = fread($this->socket, 1024);
            $buffer .= $chunk;
            
            // Check if we hit a timeout or empty read
            $info = stream_get_meta_data($this->socket);
            if ($info['timed_out'] || empty($chunk)) {
                break;
            }
        }
        
        return $buffer;
    }

    /**
     * Wait for specific prompts in the stream
     */
    private function waitPrompt($prompts, $timeout = 5)
    {
        if (!$this->socket) {
            throw new Exception("Not connected");
        }

        $buffer = '';
        $startTime = time();
        
        while (time() - $startTime < $timeout) {
            $char = fgetc($this->socket);
            
            if ($char === false) {
                break;
            }
            
            $buffer .= $char;
            
            foreach ($prompts as $p) {
                if (substr($buffer, -strlen($p)) === $p) {
                    return $buffer;
                }
            }
        }
        
        return $buffer;
    }

    /**
     * Disconnect
     */
    public function disconnect()
    {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }
    }
}
