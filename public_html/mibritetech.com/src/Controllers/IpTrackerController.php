<?php

declare(strict_types=1);

namespace MiBriTech\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IpTrackerController
{
    private string $dataFile;

    public function __construct()
    {
        $this->dataFile = __DIR__ . '/../../data/home_server.json';
    }

    /**
     * GET /api/ip - Retrieve stored home server IP
     */
    public function get(Request $request, Response $response): Response
    {
        $data = $this->loadData();

        $response->getBody()->write(json_encode([
            'success' => true,
            'ip' => $data['ip'] ?? null,
            'updated_at' => $data['updated_at'] ?? null,
            'hostname' => $data['hostname'] ?? null
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * POST /api/ip/update - Update home server IP
     *
     * Expected payload:
     * {
     *   "api_key": "your-secret-key",
     *   "ip": "xxx.xxx.xxx.xxx",
     *   "hostname": "optional-hostname",
     *   "ssh_port": 22
     * }
     */
    public function update(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        // Validate API key
        $apiKey = $_ENV['IP_TRACKER_API_KEY'] ?? '';
        if (empty($apiKey) || ($body['api_key'] ?? '') !== $apiKey) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Invalid API key'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        // Validate IP
        $ip = $body['ip'] ?? '';
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Invalid IP address'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        // Save data
        $data = [
            'ip' => $ip,
            'hostname' => $body['hostname'] ?? null,
            'ssh_port' => $body['ssh_port'] ?? 22,
            'updated_at' => date('c'),
            'updated_from_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];

        $this->saveData($data);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'IP updated successfully',
            'data' => $data
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    private function loadData(): array
    {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        $content = file_get_contents($this->dataFile);
        return json_decode($content, true) ?? [];
    }

    private function saveData(array $data): void
    {
        $dir = dirname($this->dataFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT));
    }
}
