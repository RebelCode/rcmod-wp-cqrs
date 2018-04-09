<?php

namespace RebelCode\Expression;

use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Util\String\StringableInterface as Stringable;

abstract class AbstractTerm
{
    /*
     * Provides functionality for creating invalid-argument exceptions.
     *
     * @since [*next-version*]
     */
    use CreateInvalidArgumentExceptionCapableTrait;

    /*
     * Provides string translating functionality.
     *
     * @since [*next-version*]
     */
    use StringTranslatingTrait;

    /**
     * The term type.
     *
     * @since [*next-version*]
     *
     * @var string|Stringable
     */
    protected $type;

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Retrieves the expression type.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $type The expression type.
     */
    protected function _setType($type)
    {
        if (!is_string($type) && !($type instanceof Stringable)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Expression type must be a stringable argument'),
                null,
                null,
                $type
            );
        }

        $this->type = $type;
    }
}
