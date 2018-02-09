<?php
namespace Helper;
use Constant\CacheKey;

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
        $text = preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2', $text);
        $text = preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i', '$1****$2', $text);

        //有过滤
        if (strpos($text, '*') > 0) {
            return [$text, $text_tmp];
        //无过滤
        }else{
            return [$text, ''];
        }

    }

    /**
     * 是否提示用户答谢
     * @return boolean [description]
     */
    public static function isThank($text, $room_id)
    {
        $info = json_decode(redis()->hGet(Cachekey::API_CHAT_ROOM_INFO, $room_id), true);
        if(isset($info['thank_expire'])){
            if ( $info['thank_expire']-time() > 0 ) {
                return false;
            }
        }

        $thank_words = Model('Setting')->get('thank_words_filter', '谢,thank');
        if ($thank_words=='-1') {      //-1 提示答谢失效
            return false;
        }

        $thank_words = explode(',', $thank_words);
        foreach ($thank_words as $key => $value) {
            $pos = strpos($text, $value);
            if ($pos !== false) {
                $info = $info ? : [];
                $info['thank_expire'] = time() + config()->get('thank_expire_space', 30) * 60;
                redis()->hSet(Cachekey::API_CHAT_ROOM_INFO, $room_id, json_encode($info));

                return true;
            }
        }

        return false;
    }

}