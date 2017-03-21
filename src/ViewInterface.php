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
     * @param Processor $processor
     * @param string $path
     * @param array $params
     * @return string
     */
    public function __invoke(Processor $processor, $path, array $params);
}
