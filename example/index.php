<?php
use App\PhpRenderer\Renderer;

require_once __DIR__ . '/../vendor/autoload.php';

$renderer = Renderer::createSimple(__DIR__ . '/view/');

$content = $renderer->render('01', [
    'val' => 123,
]);
echo $content;
