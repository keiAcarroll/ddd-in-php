<?php

/**
 * 再利用したいクラス
 * テキストファイルを表示するためのクラス
 */
class ShowFile
{
    /**
     * 表示するファイル名
     */
    private $filename;

    /**
     * コンストラクタ
     * ファイル名を受け取り、プロパティに格納します
     */
    public function __construct($filename)
    {
        if (!is_readable($filename)) {
            throw new Exception(
                'file "' . $filename . '" is not readable !'
            );
        }
        $this->filename = $filename;
    }

    /**
     * プレーン表示します
     */
    public function showPlain()
    {
        echo '<pre>';
        echo htmlspecialchars(
            file_get_contents($this->filename), ENT_QUOTES, mb_internal_encoding()
        );
        echo '</pre>';
    }

    /**
     * シンタックスハイライト表示します
     * （highlight_fileなんてメソッドがあることに驚きました。）
     */
    public function showHighlight()
    {
        highlight_file($this->filename);
    }
}

