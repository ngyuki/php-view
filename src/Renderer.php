<?php
namespace App\PhpRenderer;

class Renderer
{
    /**
     * @var TemplateResolver
     */
    private $resolver;

    /**
     * @var StreamFilter
     */
    private $filter;

    /**
     * callable
     */
    private $viewFactory;

    public static function createSimple($prefix, $suffix = '.phtml')
    {
        return new static(
            new TemplateResolver($prefix, $suffix),
            new StreamFilter(),
            function () {
                return new class implements ViewInterface { use ViewTrait; };
            }
        );
    }

    public function __construct(TemplateResolver $resolver, StreamFilter $filter, callable $viewFactory)
    {
        $this->resolver = $resolver;
        $this->filter = $filter;
        $this->viewFactory = $viewFactory;
    }

    public function render($file, array $params = [])
    {
        $level = ob_get_level();

        try {
            $processor = new Processor($this->resolver, $this->filter, $this->viewFactory);
            $template = $processor->process($file, $params);
            return $template->combine();
        } finally {
            while (ob_get_level() > $level) {
                ob_end_flush();
            }
        }
    }
}
