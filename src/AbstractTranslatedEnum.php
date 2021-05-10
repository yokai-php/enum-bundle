<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\Exception\InvalidTranslatePatternException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
abstract class AbstractTranslatedEnum implements EnumInterface
{
    use EnumLabelTrait;

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
    private $transDomain = 'messages';

    /**
     * @param TranslatorInterface $translator
     * @param string              $transPattern
     */
    public function __construct(TranslatorInterface $translator, string $transPattern)
    {
        if (false === strpos($transPattern, '%s')) {
            throw InvalidTranslatePatternException::placeholderRequired($transPattern);
        }

        $this->translator = $translator;
        $this->transPattern = $transPattern;
    }

    /**
     * @return array
     */
    public function getChoices(): array
    {
        return array_combine(
            $this->getValues(),
            array_map(
                function (string $value): string {
                    return $this->translator->trans(
                        sprintf($this->transPattern, $value),
                        [],
                        $this->transDomain
                    );
                },
                $this->getValues()
            )
        );
    }

    /**
     * @param string $transDomain
     */
    public function setTransDomain(string $transDomain): void
    {
        $this->transDomain = $transDomain;
    }

    /**
     * @return array
     */
    abstract protected function getValues(): array;
}
