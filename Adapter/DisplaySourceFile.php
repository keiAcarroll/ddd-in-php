<?php

/**
 * 今回、すでに宣言されているものとするインタフェース
 */
interface DisplaySourceFile
{
    /**
     * 指定されたソースファイルをハイライト表示する
     * 
     * display()メソッドはShowFileのshowHighlight()メソッドと同じ
     * なので流用したいが、ShowFileクラスはDisplaySourceFileインタフェースを実装していないので、
     * そのままでは使い回せない
     * 
     * showFileクラス自体に追加する方法もあるが、
     * 十分にテストされたクラスを変更することは
     * 使用実績を失うことになる。（テストで担保できなくもないとは思うが）
     * また、隠したいメソッド（showPlainメソッド）を隠すこともできない。
     * 
     * そこで、Adapterクラスを使用する。
     */
    public function display();

    /**
     * プレーンテキストで表示するメソッドは
     * 使わせたくないため、用意しない
     */
}
