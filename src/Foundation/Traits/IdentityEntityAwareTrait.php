<?php

namespace Chaos\Foundation\Traits;

use Chaos\Foundation\Types\Type;

/**
 * Trait IdentityEntityAwareTrait
 * @author ntd1712
 */
trait IdentityEntityAwareTrait
{
    /**
     * @Doctrine\ORM\Mapping\Column(name="id", type="integer", options={"unsigned"=true})
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @Doctrine\ORM\Mapping\Id
     */
    protected $Id;
    /**
     * @Doctrine\ORM\Mapping\Column(name="uuid", type="guid", nullable=true)
     */
    protected $Uuid;
    /**
     * @Doctrine\ORM\Mapping\Column(name="application_key", type="string", nullable=true)
     */
    private $ApplicationKey;

    /**
     * @return  string
     */
    public function getIdDataType()
    {
        return Type::INTEGER_TYPE;
    }

    /**
     * @return  integer
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * @param   integer $Id
     * @return  self
     */
    public function setId($Id)
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * @return  string
     */
    public function getUuidDataType()
    {
        return Type::UUID_TYPE;
    }

    /**
     * @return  string
     */
    public function getUuid()
    {
        return $this->Uuid;
    }

    /**
     * @param   string $Uuid
     * @return  self
     */
    public function setUuid($Uuid)
    {
        $this->Uuid = $Uuid;

        return $this;
    }

    /**
     * @return  string
     */
    public function getApplicationKeyDataType()
    {
        return Type::STRING_TYPE;
    }

    /**
     * @return  string
     */
    public function getApplicationKey()
    {
        return $this->ApplicationKey;
    }

    /**
     * @param   string $ApplicationKey
     * @return  self
     */
    public function setApplicationKey($ApplicationKey)
    {
        $this->ApplicationKey = $ApplicationKey;

        return $this;
    }
}
