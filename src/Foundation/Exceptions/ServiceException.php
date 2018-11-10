<?php

namespace Chaos\Foundation\Exceptions;

/**
 * Class ServiceException
 * @author ntd1712
 */
class ServiceException extends \RuntimeException
{
    /**
     * @var mixed|integer
     */
    protected $code = 500;
}
