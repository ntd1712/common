<?php

namespace Chaos\Foundation\Exceptions;

/**
 * Class ValidateException
 * @author ntd1712
 */
class ValidateException extends \RuntimeException
{
    /**
     * @var mixed|integer
     */
    protected $code = 418;
}
