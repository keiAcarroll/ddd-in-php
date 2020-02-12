<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once 'DisplaySourceFileImpl.php';

/**
 * DisplaySourceFileImplクラスをインスタンス化する。
 * 内容を表示するファイルは、「ShowFile.php」
 */
$show_file1 = new DisplaySourceFileImpl1('./ShowFile.php');
$show_file2 = new DisplaySourceFileImpl2('./ShowFile.php');

/**
 * DisplaySourceFileインターフェースに対してコーディングを行えば、
 * クライアント側は内部の実装を気にせず使用することができる。
 */

/**
 * プレーンテキストとハイライトしたファイル内容をそれぞれ
 * 表示する
 */
$show_file1->display();
echo '<hr>';
$show_file2->display();

/**
 * Adapterパターンは「既存クラスを再利用するために繋ぎ合わせる」といった後天的な理由で用いられます。
 * 一方のBridgeパターンは「設計の段階で実装と機能を分離し、それぞれを繋ぎ合わせる」といった先天的な理由で導入されます。
 */
