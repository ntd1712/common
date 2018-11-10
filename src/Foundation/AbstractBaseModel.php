<?php

namespace Chaos\Foundation;

/**
 * Class AbstractBaseModel
 * @author ntd1712
 */
abstract class AbstractBaseModel extends AbstractBaseObjectItem implements Contracts\IBaseModel
{
    use Traits\JsonAwareTrait;
}
