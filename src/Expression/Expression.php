<?php

namespace RebelCode\Expression;

use Dhii\Expression\ExpressionInterface;

class Expression extends AbstractExpression implements ExpressionInterface
{
    public function __construct($terms = [], $type = '')
    {
        $this->_setTerms($terms);
        $this->_setType($type);
    }
}
