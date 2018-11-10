<?php

namespace Chaos\Foundation\Events;

/**
 * Class UpdateEventArgs
 * @author ntd1712
 */
class UpdateEventArgs extends EventArgs
{
    /**
     * @var mixed
     */
    private $payload;
    /**
     * @var mixed
     */
    private $post;
    /**
     * @var mixed
     */
    private $entity;
    /**
     * @var mixed
     */
    private $master;
    /**
     * @var boolean
     */
    private $isNew;

    /**
     * Constructor.
     *
     * @param   mixed $post The $_POST.
     * @param   mixed $entity The entity.
     * @param   boolean $isNew A boolean value indicate if this $_POST is new.
     */
    public function __construct($post, $entity, $isNew)
    {
        $this->isNew = $isNew;
        $this->post = $this->payload = $post;
        $this->entity = $entity;

        if (is_object($entity)) {
            $this->master = clone $entity;
        }
    }

    /**
     * @return  boolean
     */
    public function isNew()
    {
        return $this->isNew;
    }

    /**
     * @return  mixed|array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return  mixed|array
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param   mixed $post The $_POST.
     * @return  self
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return  mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param   mixed $entity The entity.
     * @return  self
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return  mixed
     */
    public function getMaster()
    {
        return $this->master;
    }
}
