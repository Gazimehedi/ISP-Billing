<?php

namespace App\Services\Olt\Drivers;

use App\Models\Olt;
use App\Services\Olt\TelnetService;
use Exception;
use phpseclib3\Net\SSH2;
use FreeDSx\Snmp\SnmpClient;
use Illuminate\Support\Facades\Crypt;

abstract class BaseDriver implements OltDriverInterface
{
    protected $olt;
    protected $ssh;
    protected $telnet;
    protected $snmp;
    protected $connected = false;
    protected $connectionType = 'ssh'; // ssh or telnet

    public function __construct(Olt $olt)
    {
        $this->olt = $olt;
    }

    public function connect()
    {
        $host = $this->olt->host;
        $port = $this->olt->port;
        $username = $this->olt->username;
        $password = Crypt::decryptString($this->olt->password);

        try {
            if ($port == 22) {
                 $this->connectionType = 'ssh';
                 $this->ssh = new SSH2($host, $port);
                 if (!$this->ssh->login($username, $password)) {
                     throw new Exception("SSH Login failed");
                 }
                 $this->ssh->setTimeout(10);
            } else {
                // Assume Telnet for other ports (usually 23)
                $this->connectionType = 'telnet';
                $this->telnet = new TelnetService($host, $port);
                $this->telnet->connect();
                $this->telnet->login($username, $password);
            }
            $this->connected = true;
        } catch (Exception $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
    
    abstract public function parseOnuList($output);
    abstract public function parseOnuInfo($output);

    public function disconnect()
    {
        if ($this->ssh) {
            $this->ssh->disconnect();
            $this->ssh = null;
        }
        if ($this->telnet) {
            $this->telnet->disconnect();
            $this->telnet = null;
        }
        $this->connected = false;
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }
    
    protected function execute($command)
    {
        if (!$this->connected) {
             throw new Exception("Not connected");
        }

        if ($this->connectionType === 'ssh') {
            return $this->ssh->exec($command);
        } else {
            return $this->telnet->exec($command);
        }
    }
    
    protected function snmpGet($oid)
    {
        // Wrap in try/catch to avoid crashing if SNMP not configured or unreachable
        try {
            $snmp = new SnmpClient([
                'host' => $this->olt->host,
                'version' => 2,
                'community' => env('SNMP_COMMUNITY', 'public'),
                'timeout' => 2,
                'retries' => 1
            ]);
            
            $value = $snmp->getValue($oid);
            return (string)$value;
        } catch (Exception $e) {
            return null;
        }
    }
    
    protected function snmpWalk($oid)
    {
        try {
            $snmp = new SnmpClient([
                'host' => $this->olt->host,
                'version' => 2,
                'community' => env('SNMP_COMMUNITY', 'public'),
                'timeout' => 5
            ]);
            
            return $snmp->walk($oid);
        } catch (Exception $e) {
            return [];
        }
    }
}
