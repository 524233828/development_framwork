<?php

namespace Exception;

use Constant\ErrorCode;

class BaseException extends \Exception
{
    public static function SystemError()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ERR_SYSTEM),
            ErrorCode::ERR_SYSTEM
        );
    }

    public static function ParamsError()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ERR_INVALID_PARAMETER),
            ErrorCode::ERR_INVALID_PARAMETER
        );
    }

    public static function ParamsMissing()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ERR_NO_PARAMETERS),
            ErrorCode::ERR_NO_PARAMETERS
        );
    }
}
