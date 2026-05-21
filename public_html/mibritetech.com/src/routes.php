<?php

declare(strict_types=1);

use MiBriTech\Controllers\HomeController;
use MiBriTech\Controllers\IpTrackerController;
use Slim\App;

/** @var App $app */

// Public routes
$app->get('/', [HomeController::class, 'index'])->setName('home');
$app->get('/about', [HomeController::class, 'about'])->setName('about');
$app->get('/portfolio', [HomeController::class, 'portfolio'])->setName('portfolio');
$app->get('/contact', [HomeController::class, 'contact'])->setName('contact');

// IP Tracker API routes
$app->group('/api/ip', function ($group) {
    $group->get('', [IpTrackerController::class, 'get']);
    $group->post('/update', [IpTrackerController::class, 'update']);
});
