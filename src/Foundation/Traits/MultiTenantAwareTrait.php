<?php

namespace Chaos\Foundation\Traits;

use Doctrine\Common\Collections\Criteria;

/**
 * Trait MultiTenantAwareTrait (currently not in use)
 * @author ntd1712
 *
 * @method \M1\Vars\Vars __getConfig()
 */
trait MultiTenantAwareTrait
{
    /**
     * {@inheritdoc} @override
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $entity = parent::find($id, $lockMode, $lockVersion);

        if (null !== ($config = $this->__getConfig()) && $config->get('multitenant.enabled')) {
            $keymap = @call_user_func([$entity, 'get' . $config->get('multitenant.keymap')]);

            if ($config->get('framework.application_key') != $keymap) {
                $entity = null;
            }
        }

        return $entity;
    }

    /**
     * {@inheritdoc} @override
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (null !== ($config = $this->__getConfig()) && $config->get('multitenant.enabled')
            && !isset($criteria[$keymap = $config->get('multitenant.keymap')])
        ) {
            $criteria[$keymap] = $config->get('framework.application_key');
        }

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * {@inheritdoc} @override
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        if (null !== ($config = $this->__getConfig()) && $config->get('multitenant.enabled')
            && !isset($criteria[$keymap = $config->get('multitenant.keymap')])
        ) {
            $criteria[$keymap] = $config->get('framework.application_key');
        }

        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * {@inheritdoc} @override
     */
    public function matching(Criteria $criteria)
    {
        if (null !== ($config = $this->__getConfig()) && $config->get('multitenant.enabled')) {
            $criteria->andWhere(
                $criteria->expr()->eq($config->get('multitenant.keymap'), $config->get('framework.application_key'))
            );
        }

        return parent::matching($criteria);
    }
}
