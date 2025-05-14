<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    /**
     * Runs before your controller to handle OPTIONS preflight
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // If preflight, respond immediately
        if ($request->getMethod(true) === 'OPTIONS') {
            $response = service('response');
            return $response
                ->setStatusCode(200)
                ->setHeader('Access-Control-Allow-Origin',   'https://campzen-x191p.web.app')
                ->setHeader('Access-Control-Allow-Methods',  'GET, POST, PUT, DELETE, OPTIONS')
                ->setHeader('Access-Control-Allow-Credentials', 'true')
                ->setHeader('Access-Control-Allow-Headers',  'Content-Type, Authorization');
        }
    }

    /**
     * Runs after controller; adds CORS headers to all API responses
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response
            ->setHeader('Access-Control-Allow-Origin',   'https://campzen-x191p.web.app')
            ->setHeader('Access-Control-Allow-Methods',  'GET, POST, PUT, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Credentials', 'true')
            ->setHeader('Access-Control-Allow-Headers',  'Content-Type, Authorization');
        return $response;
    }
}
