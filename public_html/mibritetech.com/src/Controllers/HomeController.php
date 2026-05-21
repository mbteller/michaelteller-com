<?php

declare(strict_types=1);

namespace MiBriTech\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeController
{
    public function __construct(
        private Twig $twig
    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'home.twig', [
            'title' => 'MiBriTech - Michael Teller',
            'page' => 'home'
        ]);
    }

    public function about(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'about.twig', [
            'title' => 'About - MiBriTech',
            'page' => 'about'
        ]);
    }

    public function portfolio(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'portfolio.twig', [
            'title' => 'Portfolio - MiBriTech',
            'page' => 'portfolio'
        ]);
    }

    public function contact(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'contact.twig', [
            'title' => 'Contact - MiBriTech',
            'page' => 'contact'
        ]);
    }
}
