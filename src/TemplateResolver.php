<?php
namespace App\PhpRenderer;

/**
 * テンプレートの名前解決を行う
 *
 * 典型的には $prefix はディレクトリで $suffix は拡張子
 */
class TemplateResolver
{
    private $prefix;

    private $suffix;

    public function __construct($prefix, $suffix)
    {
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public function resolve($name)
    {
        $file = $this->prefix . $name . $this->suffix;
        return $file;
    }
}
