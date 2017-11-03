<?php


if($utf8 = $_GET[emoji]){

$arg = array(
'unicode' => utf8_to_unicode($utf8),
'utf8' => unicode_to_utf8($utf8));
echo json_encode($arg, JSON_PRETTY_PRINT);

}





function unicode_to_utf8($utf8){
return html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $utf8), ENT_NOQUOTES, 'UTF-8');
}

function utf8_to_unicode($utf8) {
    $i = 0;
    $l = strlen($utf8);

    $out = '';

    while ($i < $l) {
        if ((ord($utf8[$i]) & 0x80) === 0x00) {
            // 0xxxxxxx
            $n = ord($utf8[$i++]);
        } elseif ((ord($utf8[$i]) & 0xE0) === 0xC0) {
            // 110xxxxx 10xxxxxx
            $n =
                ((ord($utf8[$i++]) & 0x1F) <<  6) |
                ((ord($utf8[$i++]) & 0x3F) <<  0)
            ;
        } elseif ((ord($utf8[$i]) & 0xF0) === 0xE0) {
            // 1110xxxx 10xxxxxx 10xxxxxx
            $n =
                ((ord($utf8[$i++]) & 0x0F) << 12) |
                ((ord($utf8[$i++]) & 0x3F) <<  6) |
                ((ord($utf8[$i++]) & 0x3F) <<  0)
            ;
        } elseif ((ord($utf8[$i]) & 0xF8) === 0xF0) {
            // 11110xxx 10xxxxxx 10xxxxxx 10xxxxxx
            $n =
                ((ord($utf8[$i++]) & 0x07) << 18) |
                ((ord($utf8[$i++]) & 0x3F) << 12) |
                ((ord($utf8[$i++]) & 0x3F) <<  6) |
                ((ord($utf8[$i++]) & 0x3F) <<  0)
            ;
        } elseif ((ord($utf8[$i]) & 0xFC) === 0xF8) {
            // 111110xx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx
            $n =
                ((ord($utf8[$i++]) & 0x03) << 24) |
                ((ord($utf8[$i++]) & 0x3F) << 18) |
                ((ord($utf8[$i++]) & 0x3F) << 12) |
                ((ord($utf8[$i++]) & 0x3F) <<  6) |
                ((ord($utf8[$i++]) & 0x3F) <<  0)
            ;
        } elseif ((ord($utf8[$i]) & 0xFE) === 0xFC) {
            // 1111110x 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx
            $n =
                ((ord($utf8[$i++]) & 0x01) << 30) |
                ((ord($utf8[$i++]) & 0x3F) << 24) |
                ((ord($utf8[$i++]) & 0x3F) << 18) |
                ((ord($utf8[$i++]) & 0x3F) << 12) |
                ((ord($utf8[$i++]) & 0x3F) <<  6) |
                ((ord($utf8[$i++]) & 0x3F) <<  0)
            ;
        } else {
            throw new \Exception('Invalid utf-8 code point');
        }

        $n = strtoupper(dechex($n));
        $pad = strlen($n) <= 4 ? strlen($n) + strlen($n) %2 : 0;
        $n = str_pad($n, $pad, "0", STR_PAD_LEFT);

        $out .= sprintf("\u%s", $n);
    }

    return str_replace('\u','U+',$out);
}

?>
