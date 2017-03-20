<?php
namespace App\PhpRenderer;

class Renderer
{
    /**
     * @var TemplateResolver
     */
    private $resolver;

    /**
     * callable
     */
    private $viewFactory;

    public function __construct(TemplateResolver $resolver, callable $viewFactory)
    {
        $this->resolver = $resolver;
        $this->viewFactory = $viewFactory;
    }

    public function render($file, array $params = [])
    {
        $level = ob_get_level();

        try {
            $template = new TemplateInstance($file);
            $template->process($this->resolver, $this->viewFactory, $params);
            return $template->combine();
        } finally {
            while (ob_get_level() > $level) {
                ob_end_flush();
            }
        }
    }
}
