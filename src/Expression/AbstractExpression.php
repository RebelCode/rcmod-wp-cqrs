<?php

namespace RebelCode\Expression;

use Dhii\Expression\ExpressionInterface;

/**
 * Abstract functionality for an expression.
 *
 * @since [*next-version*]
 */
abstract class AbstractExpression extends AbstractTerm implements ExpressionInterface
{
    /*
     * Provides awareness of child terms.
     *
     * @since [*next-version*]
     */
    use TermsAwareTrait {
        _getTerms as public getTerms;
    }
}
