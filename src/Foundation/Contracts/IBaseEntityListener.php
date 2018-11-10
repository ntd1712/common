<?php

namespace Chaos\Foundation\Contracts;

/**
 * Interface IBaseEntityListener
 * @author ntd1712
 *
 * @link http://doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#entity-listeners-resolver
 */
interface IBaseEntityListener
{
    /**
     * The `postLoad` event.
     *
     * @param   \Chaos\Foundation\AbstractBaseEntity|\Chaos\Foundation\Contracts\IBaseEntity $entity The entity.
     * @param   \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs The event arguments.
     * @return  void
     * @throws  \Exception
     */
    public function postLoad($entity, $eventArgs);
}
