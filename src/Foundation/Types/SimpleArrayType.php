<?php

namespace Chaos\Foundation\Types;

/**
 * Class SimpleArrayType
 * @author ntd1712
 *
 * @see \Doctrine\DBAL\Types\SimpleArrayType
 */
class SimpleArrayType extends Type
{
    /**
     * {@inheritdoc} @override
     *
     * <code>
     * $value = Type::getType(Type::SIMPLE_ARRAY_TYPE)->convertToPHPValue($value);
     * </code>
     *
     * @param   mixed $value The value to convert.
     * @return  array
     */
    public function convertToPHPValue($value)
    {
        if (null === $value) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        if (is_object($value)) {
            return (array) $value;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        return explode(',', $value);
    }
}
