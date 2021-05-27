<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TranslatedEnum extends Enum
{
    /**
     * @var array
     */
    private $values;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $transPattern;

    /**
     * @var string
     */
    private $transDomain;

    /**
     * @param array<int|string, mixed> $values
     * @param TranslatorInterface      $translator
     * @param string                   $transPattern
     * @param string                   $transDomain
     * @param string|null              $name
     *
     * @throws LogicException
     */
    public function __construct(
        array $values,
        TranslatorInterface $translator,
        string $transPattern,
        string $transDomain = 'messages',
        ?string $name = null
    ) {
        if (false === strpos($transPattern, '%s')) {
            throw LogicException::placeholderRequired($transPattern);
        }

        $this->values = $values;
        $this->translator = $translator;
        $this->transPattern = $transPattern;
        $this->transDomain = $transDomain;

        parent::__construct(null, $name);
    }

    /**
     * @inheritdoc
     */
    protected function build(): array
    {
        $choices = [];
        foreach ($this->values as $key => $value) {
            $transLabel = $value;
            if (\is_string($key)) {
                $transLabel = $key;
            }

            $label = $this->translator->trans(\sprintf($this->transPattern, $transLabel), [], $this->transDomain);
            $choices[$label] = $value;
        }

        return $choices;
    }
}
