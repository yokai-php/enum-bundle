<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use LogicException;
use Yokai\EnumBundle\Exception\InvalidArgumentException;

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
     * @param string                    $name
     * @param array<string, mixed>|null $choices
     */
    public function __construct(string $name, ?array $choices)
    {
        if (__CLASS__ === static::class && $choices === null) {
            throw new LogicException(
                'When using ' . __CLASS__ . ' directly, $choices argument in __construct method cannot be null'
            );
        }

        $this->name = $name;
        $this->choices = $choices;
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
        throw new \LogicException(static::class . '::' . __FUNCTION__ . ' should have been overridden.');
    }

    private function init(): void
    {
        if ($this->choices !== null) {
            return;
        }

        $this->choices = $this->build();
    }
}
