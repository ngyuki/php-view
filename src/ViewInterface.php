<?php
namespace App\PhpRenderer;

/**
 * テンプレートファイルの $this となるインスタンスのインタフェース
 */
interface ViewInterface
{
    /**
     * 指定されたテンプレートインスタンス・ファイル・パラメータでテンプレートをインクルード
     *
     * @param TemplateInstance $template
     * @param string $path
     * @param array $params
     * @return string
     */
    public function __invoke(TemplateInstance $template, $path, array $params);
}
