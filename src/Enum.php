<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class Enum implements EnumInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array<string, mixed>|null
     */
    private $choices;

    /**
     * @param array<string, mixed>|null $choices Allowed to be null if you are extending this class
     *                                           and you have overridden the "Enum::build" method.
     * @param string|null               $name    Allowed to be null if you are extending this class
     *                                           and you want the FQCN to be the name.
     */
    public function __construct(?array $choices, ?string $name = null)
    {
        if (__CLASS__ === static::class && $choices === null) {
            throw new LogicException(
                'When using ' . __CLASS__ . ' directly, $choices argument in ' . __FUNCTION__ . ' method cannot be null'
            );
        }

        $this->choices = $choices;

        if ($name === null) {
            $name = static::class;
            if (
                \strpos($name, 'Yokai\\EnumBundle\\') === 0 // using FQCN as name is only allowed for other namespaces
                && \strpos($name, 'Yokai\\EnumBundle\\Tests\\') !== 0 // except for our tests
            ) {
                throw new LogicException(
                    'When using ' . static::class . ', $name argument in ' . __METHOD__ . ' method cannot be null'
                );
            }
        }

        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getChoices(): array
    {
        $this->init();

        return $this->choices;
    }

    /**
     * @inheritDoc
     */
    public function getValues(): array
    {
        $this->init();

        return \array_values($this->choices);
    }

    /**
     * @inheritDoc
     */
    public function getLabel($value): string
    {
        $this->init();

        $label = \array_search($value, $this->choices);
        if ($label === false) {
            throw InvalidArgumentException::enumMissingValue($this, $value);
        }

        return $label;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, mixed>
     */
    protected function build(): array
    {
        throw new LogicException(static::class . '::' . __FUNCTION__ . ' should have been overridden.');
    }

    private function init(): void
    {
        if ($this->choices !== null) {
            return;
        }

        $this->choices = $this->build();
    }
}
