<?php

declare(strict_types = 1);

require_once 'DisplaySourceFile.php';
require_once 'ShowFile.php';

/**
 * Adapterクラス
 * ShowFileクラスのshowHighlightメソッドを、
 * DisplaySourceFileインタフェースのdisplay()メソッドに適応させる
 * 
 * 再利用するコードについては一切変更を加えないで、必要となる機能を提供できるよう変更する。
 * 
 * こっちは 継承 を使うパターン
 */
class DisplaySourceFileImpl1 extends ShowFile implements DisplaySourceFile
{
    public function display()
    {
        parent::showHighlight();
    }
}

/**
 * こっちは 委譲 を使うパターン
 * 
 * 委譲...具体的な処理内容を他のクラスに任せること
 */
class DisplaySourceFileImpl2 implements DisplaySourceFile
{
    /**
     * ShowFileクラスが格納されるプロパティ
     */
    private $show_file;

    public function __construct(string $filename)
    {
        $this->show_file = new ShowFile($filename);
    }

    public function display()
    {
        $this->show_file->showHighlight();
    }
}