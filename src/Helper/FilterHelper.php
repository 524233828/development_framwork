<?php
namespace Helper;

/**
 * Filter 过滤
 * Class FilterHelper
 * @package Helper
 */
class FilterHelper
{
    /**
     * 过滤手机号码
     */
    public static function hidePhone($text)
    {
        $text_tmp = $text;

        //常规过滤,直接过滤相连的手机号
        $text = preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $text);
        $text = preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i', '$1****$2', $text);

        //有过滤
        if (strpos($text, '*') > 0) {
            return [$text, $text_tmp];
        //无过滤
        } else {
            return [$text, ''];
        }
    }
}
