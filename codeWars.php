<?php
function spinWords(string $str): string
{
    $string = explode(' ', $str);
    foreach ($string as &$world) {
        if (strlen($world) > 5) {
            $world = strrev($world);
        }
    }
    return implode(' ', $string);
}

$str = spinWords('Hey wollef sroirraw');
print_r($str);
