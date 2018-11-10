<?php

namespace Chaos\Foundation\Exceptions;

/**
 * Class ConversionException
 *
 * @see \Doctrine\DBAL\Types\ConversionException
 */
class ConversionException extends \RuntimeException
{
    /**
     * Thrown when a conversion fails.
     *
     * @param   string $value The value.
     * @param   string $toType The type to be converted to.
     * @return  self
     */
    public static function conversionFailed($value, $toType)
    {
        $value = 32 < strlen($value) ? substr($value, 0, 20) . '...' : $value;

        return new self('Could not convert value "' . $value . '" to ' . shorten($toType));
    }

    /**
     * Thrown when a conversion fails and we can make a statement about the expected format.
     *
     * @param   string $value The value.
     * @param   string $toType The type to be converted to.
     * @param   string $expectedFormat The expected format.
     * @param   null|\Exception $previous The previous Exception instance.
     * @return  self
     */
    public static function conversionFailedFormat($value, $toType, $expectedFormat, \Exception $previous = null)
    {
        $value = 32 < strlen($value) ? substr($value, 0, 20) . '...' : $value;

        return new self(
            'Could not convert value "' . $value . '" to ' . shorten($toType)
            . '. Expected format: ' . $expectedFormat, 0, $previous
        );
    }

    /**
     * @param   string $name The column type name.
     * @return  self
     */
    public static function unknownColumnType($name)
    {
        return new self('Unknown column type "' . $name . '" requested');
    }
}
