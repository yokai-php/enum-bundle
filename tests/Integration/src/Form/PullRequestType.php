<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yokai\EnumBundle\Tests\Integration\App\Model\PullRequest;

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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', PullRequest::class);
    }
}
