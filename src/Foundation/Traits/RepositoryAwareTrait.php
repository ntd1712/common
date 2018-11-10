<?php

namespace Chaos\Foundation\Traits;

use Doctrine\ORM\Events;

/**
 * Trait RepositoryAwareTrait
 * @author ntd1712
 *
 * @method \Symfony\Component\DependencyInjection\ContainerBuilder __getContainer()
 */
trait RepositoryAwareTrait
{
    private static $br5bfo56wz = [];

    /**
     * Gets a reference to the repository object. The object returned will be of type <tt>IBaseRepository</tt>.
     *
     * <code>
     * $this->getService()->getRepository('User')->...
     * $this->getService('User')->getRepository('Role')->...
     * $this->getService('Account\Services\UserService')->getRepository('Account\Entities\Role')->...
     * </code>
     *
     * @param   null|string $name The repository name.
     * @param   boolean $cache Defaults to TRUE.
     * @return  mixed|\Chaos\Foundation\AbstractDoctrineRepository|\Chaos\Foundation\Contracts\IBaseRepository
     * @throws  \Exception
     */
    public function getRepository($name = null, $cache = true)
    {
        if (isset(self::$br5bfo56wz[$name]) && $cache) {
            return self::$br5bfo56wz[$name];
        }

        if (empty($name)) {
            $name = str_replace(['Repository', 'Service'], '', shorten(get_called_class()));
            $repositoryName = $name . 'Repository';
        } else {
            $repositoryName = $name;
        }

        $container = $this->__getContainer();
        $config = $this->__getConfig();

        self::$br5bfo56wz[$repositoryName] = $container->get(DOCTRINE_ENTITY_MANAGER)
            ->getRepository(get_class($container->get($name)))
                ->__setContainer($container)
                ->__setConfig($config);

        // register 'postLoad' listeners
        foreach (self::$br5bfo56wz[$repositoryName]->metadata->entityListeners as $event => $listeners) {
            if (Events::postLoad === $event) {
                foreach ($listeners as $listener) {
                    self::$br5bfo56wz[$repositoryName]->entityManager->getConfiguration()
                        ->getEntityListenerResolver()->register(
                            $container->get($listener['class'])
                                ->__setContainer($container)
                                ->__setConfig($config)
                        );
                }
            }
        }

        return self::$br5bfo56wz[null] = self::$br5bfo56wz[$repositoryName];
    }
}
