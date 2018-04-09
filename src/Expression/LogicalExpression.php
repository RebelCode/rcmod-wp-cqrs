<?php

namespace RebelCode\Expression;

use Dhii\Expression\LogicalExpressionInterface;

class LogicalExpression extends AbstractExpression implements LogicalExpressionInterface
{
    use NegationAwareTrait {
        _getNegation as public isNegated;
    }

    public function __construct($terms = [], $negation = false, $type = '')
    {
        $this->_setTerms($terms);
        $this->_setNegation($negation);
        $this->_setType($type);
    }
}
