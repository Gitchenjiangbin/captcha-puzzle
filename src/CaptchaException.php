<?php

namespace Chenjiangbin\CaptchaPuzzle;

/**
 * Class CaptchaException
 * 自定义异常，用于验证码生成与验证中的错误处理
 */
class CaptchaException extends \Exception
{
    /**
     * CaptchaException constructor.
     *
     * @param string $message 异常信息
     * @param int $code 异常代码
     * @param \Throwable|null $previous 前一个异常
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
