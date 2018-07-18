<?php

namespace AAP\Token;

class TokenPrinter
{
    public static function getPrettyPrinted(
        $name,
        $token
    )
    {
        $prettyprint = '"' . $name . '":' . PHP_EOL;
        $prettyprint .= json_encode($token->getClaims(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $prettyprint . PHP_EOL;
    }
}
