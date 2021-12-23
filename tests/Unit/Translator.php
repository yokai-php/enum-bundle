<?php
// phpcs:ignoreFile

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Contracts\Translation\TranslatorInterface;

if (Kernel::MAJOR_VERSION >= 6) {
    /**
     * @author Yann Eugoné <eugone.yann@gmail.com>
     */
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

        public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
        {
            if ($domain !== $this->domain) {
                return $id;
            }

            return $this->map[$id] ?? $id;
        }
    }
} else {
    /**
     * @author Yann Eugoné <eugone.yann@gmail.com>
     */
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

        public function trans($id, array $parameters = [], $domain = null, $locale = null)
        {
            if ($domain !== $this->domain) {
                return $id;
            }

            return $this->map[$id] ?? $id;
        }
    }
}
