<?php

namespace Chaos\Foundation\Traits;

use Chaos\Foundation\Types\Type;

/**
 * Trait AuditEntityAwareTrait
 * @author ntd1712
 */
trait AuditEntityAwareTrait
{
    /**
     * @Doctrine\ORM\Mapping\Column(name="added_at", type="datetime", nullable=true)
     */
    private $AddedAt;
    /**
     * @Doctrine\ORM\Mapping\Column(name="added_by", type="string", nullable=true)
     */
    private $AddedBy;
    /**
     * @Doctrine\ORM\Mapping\Column(name="edited_at", type="datetime", nullable=true)
     */
    private $EditedAt;
    /**
     * @Doctrine\ORM\Mapping\Column(name="edited_by", type="string", nullable=true)
     */
    private $EditedBy;
    /**
     * @Doctrine\ORM\Mapping\Column(name="is_deleted", type="boolean", nullable=true)
     */
    private $IsDeleted;
    /**
     * @Doctrine\ORM\Mapping\Column(name="version", type="integer", nullable=true)
     * @Doctrine\ORM\Mapping\Version
     */
    private $Version;

    /**
     * @return  string
     */
    public function getAddedAtDataType()
    {
        return Type::DATETIME_TYPE;
    }

    /**
     * @return  \DateTime
     */
    public function getAddedAt()
    {
        return $this->AddedAt;
    }

    /**
     * @param   \DateTime $AddedAt
     * @return  self
     */
    public function setAddedAt($AddedAt)
    {
        $this->AddedAt = $AddedAt;

        return $this;
    }

    /**
     * @return  string
     */
    public function getAddedByDataType()
    {
        return Type::STRING_TYPE;
    }

    /**
     * @return  string
     */
    public function getAddedBy()
    {
        return $this->AddedBy;
    }

    /**
     * @param   string $AddedBy
     * @return  self
     */
    public function setAddedBy($AddedBy)
    {
        $this->AddedBy = $AddedBy;

        return $this;
    }

    /**
     * @return  string
     */
    public function getEditedAtDataType()
    {
        return Type::DATETIME_TYPE;
    }

    /**
     * @return  \DateTime
     */
    public function getEditedAt()
    {
        return $this->EditedAt;
    }

    /**
     * @param   \DateTime $EditedAt
     * @return  self
     */
    public function setEditedAt($EditedAt)
    {
        $this->EditedAt = $EditedAt;

        return $this;
    }

    /**
     * @return  string
     */
    public function getEditedByDataType()
    {
        return Type::STRING_TYPE;
    }

    /**
     * @return  string
     */
    public function getEditedBy()
    {
        return $this->EditedBy;
    }

    /**
     * @param   string $EditedBy
     * @return  self
     */
    public function setEditedBy($EditedBy)
    {
        $this->EditedBy = $EditedBy;

        return $this;
    }

    /**
     * @return  string
     */
    public function getIsDeletedDataType()
    {
        return Type::BOOLEAN_TYPE;
    }

    /**
     * @return  boolean
     */
    public function getIsDeleted()
    {
        return $this->IsDeleted;
    }

    /**
     * @param   boolean $IsDeleted
     * @return  self
     */
    public function setIsDeleted($IsDeleted)
    {
        $this->IsDeleted = $IsDeleted;

        return $this;
    }

    /**
     * @return  string
     */
    public function getVersionDataType()
    {
        return Type::INTEGER_TYPE;
    }

    /**
     * @return  integer
     */
    public function getVersion()
    {
        return $this->Version;
    }

    /**
     * @param   integer $Version
     * @return  self
     */
    public function setVersion($Version)
    {
        $this->Version = $Version;

        return $this;
    }
}
