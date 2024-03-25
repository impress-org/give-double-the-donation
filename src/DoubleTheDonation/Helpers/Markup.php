<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\Helpers;

class Markup
{
    public static function anchor($url, $text): string
    {
        return sprintf('<a href="%s" target="_blank">%s</a>', $url, $text) . PHP_EOL;
    }

    public static function preformatted($content): string
    {
        return sprintf('<pre style="text-align:left;padding:20px;max-height:250px;overflow-y:auto;border:1px solid #f2f2f2;">%s</pre>', $content) . PHP_EOL;
    }
}
