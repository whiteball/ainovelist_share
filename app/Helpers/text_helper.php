<?php

/**
 * 改行文字を統一する関数
 *
 * Windows改行(\r\n)、Mac改行(\r)、Unix改行(\n)をすべて\nに統一する
 *
 * @param string $text 統一対象のテキスト
 *
 * @return string 改行文字が統一されたテキスト
 */
function normalize_newlines(string $text): string
{
    // \r\n を \n に変換
    $text = str_replace("\r\n", "\n", $text);

    // \r を \n に変換
    return str_replace("\r", "\n", $text);
}
