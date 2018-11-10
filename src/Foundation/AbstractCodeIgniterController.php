<?php

namespace Chaos\Foundation;

use Chaos\Bridge\Doctrine\EntityManagerFactory;
use Ramsey\Uuid\Uuid;

if (defined('REST_Controller')) {
    class Controller extends \Restserver\Libraries\REST_Controller
    {
    }
} else {
    class Controller extends \CI_Controller
    {
    }
}

/**
 * Class AbstractCodeIgniterController
 * @author ntd1712
 *
 * @property-read object $input
 * @property-read object $session
 */
abstract class AbstractCodeIgniterController extends Controller
{
    use Traits\ConfigAwareTrait, Traits\ContainerAwareTrait, Traits\ServiceAwareTrait,
        BaseControllerTrait;

    /**
     * Constructor.
     *
     * @param   \ArrayAccess|array $container An array holding the paths to the service files.
     * @param   \ArrayAccess|array $config An array holding the paths to the config files.
     */
    public function __construct($container = [], $config = [])
    {
        parent::__construct();

        $this->__setContainer($container)->__setConfig($config);
        $this->__getContainer()->set(
            DOCTRINE_ENTITY_MANAGER,
            EntityManagerFactory::createInstance($this->__getConfig())->getEntityManager()
        );
    }

    /**
     * Either get a query value or all of the input and files.
     *
     * @param   null|string $key The request parameter key.
     * @param   mixed $default [optional] The default value.
     * @return  array|mixed
     */
    protected function getRequest($key = null, $default = null)
    {
        if (defined('REST_Controller')) {
            $request = [];

            foreach (['get', 'delete', 'post', 'put'] as $v) {
                if ('head' !== $v) {
                    $request += $this->{'_' . $v . '_args'};
                }
            }
        } else {
            $request = (array) @json_decode($this->input->raw_input_stream, true) + $this->input->post_get(null);
        }

        if (isset($key)) {
            return isset($request[$key]) ? $request[$key] : $default;
        }

        if (null === $default) {
            $vars = $request;
            $vars['EditedAt'] = 'now';
            $vars['EditedBy'] = $this->session->userdata('loggedName');
            $vars['IsDeleted'] = 'false';
            $vars['Uuid'] = Uuid::uuid4()->toString();
            $vars['ApplicationKey'] = $this->__getConfig()->get('framework.application_key');

            return $vars;
        }

        return $request;
    }
}
