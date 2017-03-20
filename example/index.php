<?php
use App\PhpRenderer\Renderer;
use App\PhpRenderer\TemplateResolver;
use App\PhpRenderer\ViewInterface;
use App\PhpRenderer\ViewTrait;

require_once __DIR__ . '/../vendor/autoload.php';

$view = new Renderer(new TemplateResolver(__DIR__ . '/view/', '.phtml'), function () {
    return new class implements ViewInterface { use ViewTrait; };
});

$content = $view->render('01', [
    'val' => 123,
]);
echo $content;
