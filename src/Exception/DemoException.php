<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/9
 * Time: 19:22
 */

namespace Exception;

use Constant\ErrorCode;

class DemoException extends BaseException
{
    public static function DemoNotFound()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::DEMO_NOT_FOUND),
            ErrorCode::DEMO_NOT_FOUND
        );
    }
}
