<?php
namespace App\PhpRenderer;

class Processor
{
    /**
     * TemplateResolver
     */
    private $resolver;

    /**
     * @var callable
     */
    private $viewFactory;

    /**
     * @var TemplateInstance
     */
    private $template;

    /**
     * @var Block[]
     */
    private $blockStack = [];

    public function __construct(TemplateResolver $resolver, callable $viewFactory)
    {
        $this->resolver = $resolver;
        $this->viewFactory = $viewFactory;
    }

    public function process($file, array $params = [])
    {
        $template = $this->template = new TemplateInstance($this->resolver->resolve($file));

        while ($this->template) {

            $this->blockStack[] = $this->template->getRootBlock();

            ob_start(function ($str) {
                end($this->blockStack)->addText($str);
            });

            $view = ($this->viewFactory)();
            $view($this, $this->template->getPath(), $params);

            ob_end_flush();

            array_pop($this->blockStack);
            assert(count($this->blockStack) === 0);

            $this->template = $this->template->getParent();
        }

        return $template;
    }

    public function extend($file)
    {
        $this->template->setParent(new TemplateInstance($this->resolver->resolve($file)));
    }

    public function block($name)
    {
        ob_flush();

        $block = new Block($name);

        $current = end($this->blockStack);
        $current->addBlock($block);

        $this->blockStack[] = $block;
        $this->template->addBlock($block);
    }

    public function endblock()
    {
        assert(count($this->blockStack) > 1);

        ob_flush();

        array_pop($this->blockStack);
    }

    public function parent()
    {
        assert($this->template->getParent() !== null);
        assert(count($this->blockStack) > 1);

        ob_flush();

        $current = end($this->blockStack);
        $current->addParentBlock($this->template->getParent());
    }
}
