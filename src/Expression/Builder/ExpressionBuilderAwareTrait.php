<?php

namespace RebelCode\Expression\Builder;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Provides awareness of an expression builder.
 *
 * @todo  Change expression builder type, when an interface for it has been decided
 *
 * @since [*next-version*]
 */
trait ExpressionBuilderAwareTrait
{
    /**
     * The expression builder.
     *
     * @since [*next-version*]
     *
     * @var ExpressionBuilder|null
     */
    protected $exprBuilder;

    /**
     * Retrieves the expression builder associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return ExpressionBuilder|null The expression builder instance, if any.
     */
    protected function _getExprBuilder()
    {
        return $this->exprBuilder;
    }

    /**
     * Sets the expression builder for this instance.
     *
     * @since [*next-version*]
     *
     * @param ExpressionBuilder|null $exprBuilder The expression builder instance, if any.
     */
    protected function _setExprBuilder($exprBuilder)
    {
        if ($exprBuilder !== null && !($exprBuilder instanceof ExpressionBuilder)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not an expression builder'), null, null, $exprBuilder
            );
        }

        $this->exprBuilder = $exprBuilder;
    }

    /**
     * Creates a new invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|int|float|bool|null $message  The message, if any.
     * @param int|float|string|Stringable|null      $code     The numeric error code, if any.
     * @param RootException|null                    $previous The inner exception, if any.
     * @param mixed|null                            $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
        $message = null,
        $code = null,
        RootException $previous = null,
        $argument = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see   sprintf()
     * @see   _translate()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
