<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
interface EnumInterface
{
    /**
     * Returns enum choices (labels as keys, values as labels)
     *
     * @return array<string, mixed>
     */
    public function getChoices(): array;

    /**
     * Returns enum values.
     *
     * @return array<int, mixed>
     */
    public function getValues(): array;

    /**
     * Returns enum value label.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getLabel($value): string;

    /**
     * Returns enum identifier (must be unique across app).
     *
     * @return string
     */
    public function getName(): string;
}
