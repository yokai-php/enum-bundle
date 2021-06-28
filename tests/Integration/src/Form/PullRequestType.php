<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('status');
        $builder->add('labels');
    }
}
