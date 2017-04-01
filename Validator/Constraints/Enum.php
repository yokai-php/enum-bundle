<?php

namespace Yokai\EnumBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Choice;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Enum extends Choice
{
    public $enum;

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'enum';
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'enum';
    }
}
