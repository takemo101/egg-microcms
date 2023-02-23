<?php

namespace Module\View\Latte;

use Latte\Loaders\FileLoader;


/**
 * パス指定をカスタマイズした
 * Latteのテンプレートローダー
 */
class LatteFileLoader extends FileLoader
{
    /**
     * テンプレートファイルの拡張子
     */
    public const Extension = '.latte.html';

    public const Separator = '.';

    /**
     * Returns template source code.
     */
    public function getContent(string $fileName): string
    {
        return parent::getContent($this->toPath($fileName));
    }

    /**
     * ファイルパスに変換する
     *
     * @param string $file
     * @return string
     */
    private function toPath(string $file): string
    {
        return str_replace(self::Separator, '/', $file) . self::Extension;
    }
}
