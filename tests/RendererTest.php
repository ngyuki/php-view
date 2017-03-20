<?php
namespace Test;

use App\PhpRenderer\Renderer;
use App\PhpRenderer\TemplateResolver;
use App\PhpRenderer\ViewInterface;
use App\PhpRenderer\ViewTrait;

class RendererTest extends \PHPUnit_Framework_TestCase
{
    function test_01()
    {
        $view = new Renderer(new TemplateResolver(__DIR__ . '/../example/view/', '.phtml'), function () {
            return new class implements ViewInterface { use ViewTrait; };
        });

        $content = $view->render('01');
        assertEquals(file_get_contents(__DIR__ . '/_files/test_01.html'), $content);
    }
}
