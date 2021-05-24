<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 *
 * NEXT_MAJOR: Add all these methods to the interface by uncommenting them.
 *
 * @method string getLabel(string $value)
 */
interface EnumInterface
{
    /**
     * Returns enum choices (value as keys, labels as values)
     *
     * @return array
     */
    public function getChoices(): array;

    /**
     * Returns enum identifier (must be unique across your app).
     *
     * @return string
     */
    public function getName(): string;

    /**
     * NEXT_MAJOR: uncomment this method
     *
     * @return string
     */
//    public function getLabel(string $value): string;
}
