<?php
namespace App\PhpRenderer;

class TemplateInstance
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var TemplateInstance
     */
    private $parent;

    /**
     * @var Block
     */
    private $rootBlock;

    /**
     * @var Block[]
     */
    private $blockByName = [];

    /**
     * @var Block[]
     */
    private $blockStack = [];

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function process(TemplateResolver $resolver, callable $viewFactory, array $params = [])
    {
        $this->rootBlock =  new Block($this);
        $this->blockStack[] = $this->rootBlock;

        ob_start(function ($str) {
            end($this->blockStack)->addText($str);
        });

        $path = $resolver->resolve($this->file);

        $view = ($viewFactory)();
        $view($this, $path, $params);

        ob_end_flush();

        array_pop($this->blockStack);
        assert(count($this->blockStack) === 0);

        if ($this->parent) {
            $this->parent->process($resolver, $viewFactory, $params);
        }
    }

    /**
     * テンプレートのブロックを結合して文字列化
     *
     * @return string
     */
    public function combine()
    {
        $template = $this;
        while ($template->parent) {
            $template = $template->parent;
        }
        return $template->rootBlock->toString($this);
    }

    /**
     * 名前でブロックを取得する
     *
     * @param string $name
     * @return Block|null
     */
    public function getBlock($name)
    {
        if (isset($this->blockByName[$name])) {
            return $this->blockByName[$name];
        }
        if ($this->parent) {
            return $this->parent->getBlock($name);
        }
        return null;
    }

    public function extend($file)
    {
        assert($this->parent === null);

        $this->parent = new TemplateInstance($file);
    }

    public function block($name)
    {
        ob_flush();

        $block = new Block($name);

        $current = end($this->blockStack);
        $current->addBlock($block);

        $this->blockStack[] = $block;
        $this->blockByName[$name] = $block;
    }

    public function endblock()
    {
        assert(count($this->blockStack) > 1);

        ob_flush();

        array_pop($this->blockStack);
    }

    public function parent()
    {
        assert($this->parent !== null);
        assert(count($this->blockStack) > 1);

        ob_flush();

        $current = end($this->blockStack);
        $current->addParentBlock($this->parent);
    }
}
