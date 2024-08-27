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
     * @var array<int|string, mixed>
     */
    private array $values;

    private TranslatorInterface $translator;

    private string $transPattern;

    private string $transDomain;

    /**
     * @param array<int|string, mixed> $values
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
        if (!\str_contains($transPattern, '%s')) {
            throw LogicException::placeholderRequired($transPattern);
        }

        $this->values = $values;
        $this->translator = $translator;
        $this->transPattern = $transPattern;
        $this->transDomain = $transDomain;

        parent::__construct(null, $name);
    }

    protected function build(): array
    {
        $choices = [];
        foreach ($this->values as $key => $value) {
            $transLabel = $value;
            if (\is_string($key)) {
                $transLabel = $key;
            }
            if (!\is_scalar($transLabel)) {
                $transLabel = $key;
            }

            $label = $this->translator->trans(\sprintf($this->transPattern, $transLabel), [], $this->transDomain);
            $choices[$label] = $value;
        }

        return $choices;
    }
}
