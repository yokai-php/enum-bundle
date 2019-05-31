<?php declare(strict_types=1);

namespace Yokai\Enum;


use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConfigurableTranslatedEnum extends AbstractTranslatedEnum
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $values;

    /**
     * @param TranslatorInterface $translator
     * @param string              $transPattern
     * @param string              $name
     * @param array               $values
     */
    public function __construct(TranslatorInterface $translator, string $transPattern, string $name, array $values)
    {
        parent::__construct($translator, $transPattern);

        $this->name = $name;
        $this->values = $values;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    protected function getValues(): array
    {
        return $this->values;
    }
}
