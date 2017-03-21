<?php
namespace App\PhpRenderer;

class TemplateInstance
{
    /**
     * @var string
     */
    private $path;

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
    private $blocks = [];

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->rootBlock = new Block(null);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Block
     */
    public function getRootBlock()
    {
        return $this->rootBlock;
    }

    /**
     * @param string $name
     * @return Block|null
     */
    public function getBlock($name)
    {
        if (isset($this->blocks[$name])) {
            return $this->blocks[$name];
        }
        if ($this->parent) {
            return $this->parent->getBlock($name);
        }
        return null;
    }

    /**
     * @param Block $block
     */
    public function addBlock(Block $block)
    {
        assert(array_key_exists($block->getName(), $this->blocks) == false);

        $this->blocks[$block->getName()] = $block;
    }

    /**
     * @return TemplateInstance
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param TemplateInstance $parent
     */
    public function setParent(TemplateInstance $parent)
    {
        assert($this->parent === null);

        $this->parent = $parent;
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
}
