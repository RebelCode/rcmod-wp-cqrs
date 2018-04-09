<?php

namespace RebelCode\Expression;

use Dhii\Data\ValueAwareTrait;
use Dhii\Expression\LiteralTermInterface;

class LiteralTerm extends AbstractTerm implements LiteralTermInterface
{
    use ValueAwareTrait {
        _getValue as public getValue;
    }

    const DEFAULT_TYPE = 'literal';

    public function __construct($value, $type = self::DEFAULT_TYPE)
    {
        $this->_setValue($value);
        $this->_setType($type);
    }
}
