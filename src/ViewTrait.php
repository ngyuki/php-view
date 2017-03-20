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
     * @var TemplateInstance
     */
    private $template;

    public function __invoke(TemplateInstance $template, $path, array $params)
    {
        $this->template = $template;

        (function () {
            extract(func_get_arg(1));
            /** @noinspection PhpIncludeInspection */
            include func_get_arg(0);
        })($path, $params);
    }

    public function extend($file)
    {
        $this->template->extend($file);
        return null;
    }

    public function block($name)
    {
        $this->template->block($name);
        return null;
    }

    public function endblock()
    {
        $this->template->endblock();
        return null;
    }

    public function parent()
    {
        $this->template->parent();
        return null;
    }
}
