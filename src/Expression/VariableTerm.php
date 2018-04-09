<?php

namespace RebelCode\Expression;

use Dhii\Data\KeyAwareTrait;
use Dhii\Expression\VariableTermInterface;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;

class VariableTerm extends AbstractTerm implements VariableTermInterface
{
    use KeyAwareTrait {
        _getKey as public getKey;
    }

    use NormalizeStringCapableTrait;

    public function __construct($key, $type = '')
    {
        $this->_setKey($key);
        $this->_setType($type);
    }
}
