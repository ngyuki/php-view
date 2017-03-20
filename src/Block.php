<?php
namespace App\PhpRenderer;

/**
 * テンプレートファイルの中の１つのブロックに相当するクラス
 */
class Block
{
    private $name;

    private $children = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * サブブロックを追加
     *
     * @param Block $block
     */
    public function addBlock(Block $block)
    {
        $this->children[] = $block;
    }

    /**
     * 親のブロックを追加
     *
     * @param TemplateInstance $parent
     */
    public function addParentBlock(TemplateInstance $parent)
    {
        $this->children[] = $parent;
    }

    /**
     * テキストを追加
     *
     * @param string $text
     */
    public function addText($text)
    {
        $this->children[] = $text;
    }

    /**
     * ブロックを文字列化
     *
     * @param TemplateInstance $template
     * @return string
     */
    public function toString(TemplateInstance $template)
    {
        $result = [];

        foreach ($this->children as $child) {
            if ($child instanceof Block) {
                // サブブロックの名前を用いてテンプレートからブロックを取得して表示
                // オーバーライドされている可能性があるので $child を直接表示してはダメ
                $result[] = $template->getBlock($child->name)->toString($template);
            } else if ($child instanceof TemplateInstance) {
                // 自身の名前を用いて親のテンプレートからブロックを取り出して表示
                $result[] = $child->getBlock($this->name)->toString($template);
            } else {
                $result[] = $child;
            }
        }

        return implode('', $result);
    }
}
