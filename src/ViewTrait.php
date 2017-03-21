<?php
namespace App\PhpRenderer;

/**
 * ViewInterface を実装するトレイト
 *
 * 継承のためのメソッドを追加する
 */
trait ViewTrait
{
    /**
     * @var Processor
     */
    private $processor;

    public function __invoke(Processor $processor, $path, array $params)
    {
        $this->processor = $processor;

        (function () {
            extract(func_get_arg(1));
            /** @noinspection PhpIncludeInspection */
            include func_get_arg(0);
        })($path, $params);
    }

    public function extend($file)
    {
        $this->processor->extend($file);
        return null;
    }

    public function block($name)
    {
        $this->processor->block($name);
        return null;
    }

    public function endblock()
    {
        $this->processor->endblock();
        return null;
    }

    public function parent()
    {
        $this->processor->parent();
        return null;
    }
}
