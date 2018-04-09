<?php

namespace RebelCode\Expression;

use Dhii\Expression\VariableTermInterface;
use Dhii\Storage\Resource\Sql\EntityFieldInterface;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;

class EntityFieldTerm extends AbstractTerm implements EntityFieldInterface, VariableTermInterface
{
    use EntityAwareTrait {
        _getEntity as public getEntity;
    }

    use FieldAwareTrait {
        _getField as public getField;
    }

    use NormalizeStringCapableTrait;

    public function __construct($entity, $field, $type = '')
    {
        $this->_setEntity($entity);
        $this->_setField($field);
        $this->_setType($type);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getKey()
    {
        return sprintf('%1$s.%2$s', $this->entity, $this->field);
    }
}
