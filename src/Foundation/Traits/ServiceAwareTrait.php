<?php

namespace Chaos\Foundation\Traits;

/**
 * Trait ServiceAwareTrait
 * @author ntd1712
 *
 * @method \Symfony\Component\DependencyInjection\ContainerBuilder __getContainer()
 */
trait ServiceAwareTrait
{
    private static $zin2bt24bb = [];

    /**
     * Gets a reference to the service object. The object returned will be of type <tt>IBaseService</tt>.
     *
     * <code>
     * $this->getService()->...
     * $this->getService('Lookup')->...
     * $this->getService('System\Services\LookupService')->...
     * </code>
     *
     * @param   null|string $name The service name.
     * @param   boolean $cache [optional] Defaults to TRUE.
     * @return  \Chaos\Foundation\AbstractBaseService|\Chaos\Foundation\Contracts\IBaseService
     * @throws  \Exception
     */
    public function getService($name = null, $cache = true)
    {
        if (isset(self::$zin2bt24bb[$name]) && $cache) {
            return self::$zin2bt24bb[$name];
        }

        if (empty($name)) {
            if (false === strpos($name = get_called_class(), '\\')) {
                $serviceName = $name . 'Service';
            } else {
                $serviceName = str_replace(['Controller', 'Service'], '', shorten($name)) . 'Service';
            }
        } else if (false === strpos($name, '\\')) {
            $serviceName = str_replace('Service', '', $name) . 'Service';
        } else {
            $serviceName = $name;
        }

        $container = $this->__getContainer();

        return self::$zin2bt24bb[null] = self::$zin2bt24bb[$serviceName] = $container->get($serviceName)
            ->__setContainer($container)
            ->__setConfig($this->__getConfig());
    }
}
