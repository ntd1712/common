<?php

namespace Chaos\Foundation;

/**
 * Class AbstractBaseEntityListener
 * @author ntd1712
 */
abstract class AbstractBaseEntityListener implements Contracts\IBaseEntityListener
{
    use Traits\ConfigAwareTrait, Traits\ContainerAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param   \Chaos\Foundation\AbstractBaseEntity|\Chaos\Foundation\Contracts\IBaseEntity $entity The entity.
     * @param   \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs The event arguments.
     * @return  void
     */
    public function postLoad($entity, $eventArgs)
    {
        $entity
            ->setEntityIdentifier($eventArgs->getEntityManager()->getUnitOfWork()->getEntityIdentifier($entity))
            ->__setContainer($this->__getContainer())
            ->__setConfig($this->__getConfig());
    }
}
