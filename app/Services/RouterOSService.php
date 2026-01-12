<?php

namespace App\Services;

use Exception;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class RouterOSService
{
    protected $client;
    protected $error;
    protected $connected = false;

    /**
     * Connect to Mikrotik Router
     */
    public function connect($ip, $username, $password, $port = 8728)
    {
        try {
            $config = new Config([
                'host' => $ip,
                'user' => $username,
                'pass' => $password,
                'port' => (int) $port,
                'timeout' => 5,
                'attempts' => 1,
            ]);

            $this->client = new Client($config);
            // Verify connection by making a lightweight call or relying on Exception
            // The Client constructor might not connect immediately until query? 
            // Actually it establishes socket.
            $this->connected = true;
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->connected = false;
            return false;
        }
    }

    /**
     * Send command requests
     */
    public function comm($command, $params = [])
    {
        if (!$this->connected || !$this->client) return false;

        try {
            $query = new Query($command);
            
            foreach ($params as $key => $value) {
                if (strpos($key, '?') === 0) {
                    $query->where(substr($key, 1), $value);
                } else {
                    $query->equal($key, $value);
                }
            }
            
            return $this->client->query($query)->read();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function disconnect()
    {
        $this->client = null;
        $this->connected = false;
    }

    public function getError()
    {
        return $this->error;
    }
}
