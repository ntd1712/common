<?php

namespace Chaos\Foundation\Traits;

/**
 * Trait EntityIdentifierAwareTrait
 * @author ntd1712
 */
trait EntityIdentifierAwareTrait
{
    /**
     * @JMS\Serializer\Annotation\Exclude()
     */
    private static $mkg2q6cmyz = [];

    /**
     * Gets the identifier of an entity.
     *
     * @return  array
     */
    public function getEntityIdentifier()
    {
        return self::$mkg2q6cmyz;
    }

    /**
     * Sets the identifier of an entity.
     *
     * @param   array $identifier The identifier values.
     * @return  static
     */
    public function setEntityIdentifier(array $identifier)
    {
        self::$mkg2q6cmyz = $identifier;

        return $this;
    }
}
