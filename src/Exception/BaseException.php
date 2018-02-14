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

    public static function SignError()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ERR_CHECK_SIGN),
            ErrorCode::ERR_CHECK_SIGN
        );
    }

    public static function RequestOvertime()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ERR_OVERTIME),
            ErrorCode::ERR_OVERTIME
        );
    }
}
