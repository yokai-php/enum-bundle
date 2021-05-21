<?php

namespace Yokai\EnumBundle\Tests;

use Symfony\Contracts\Translation\TranslatorInterface;

class Translator implements TranslatorInterface
{
    /**
     * @var array
     */
    private $map;

    /**
     * @var string
     */
    private $domain;

    public function __construct(array $map, string $domain = 'messages')
    {
        $this->map = $map;
        $this->domain = $domain;
    }

    public function getLocale(): string
    {
        return 'fr';
    }

    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        if ($domain !== $this->domain) {
            return $id;
        }

        return $this->map[$id] ?? $id;
    }
}
