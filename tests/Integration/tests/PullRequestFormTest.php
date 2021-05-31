<?php

declare(strict_types=1);

namespace Integration\tests;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestLabelEnum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestStatusEnum;
use Yokai\EnumBundle\Tests\Integration\App\Form\PullRequestType;
use Yokai\EnumBundle\Tests\Integration\App\Model\PullRequest;
use Yokai\EnumBundle\Tests\Integration\App\Model\Status;

final class PullRequestFormTest extends KernelTestCase
{
    public function testBuildForm(): void
    {
        $container = self::bootKernel()->getContainer();

        $model = new PullRequest();
        $model->status = Status::MERGED();
        $model->labels = ['3.x', 'feature'];

        /** @var FormInterface $form */
        $form = $container->get('form.factory')->create(PullRequestType::class, $model);

        $status = $form->get('status');
        self::assertEquals(Status::MERGED(), $status->getData());
        self::assertEquals(Status::MERGED(), $status->getNormData());
        self::assertSame(EnumType::class, \get_class($status->getConfig()->getType()->getInnerType()));
        self::assertSame(PullRequestStatusEnum::class, $status->getConfig()->getOption('enum'));
        self::assertEquals(
            ['Opened' => Status::OPENED(), 'Merged' => Status::MERGED(), 'Closed' => Status::CLOSED()],
            $status->getConfig()->getOption('choices')
        );

        $labels = $form->get('labels');
        self::assertEquals(['3.x', 'feature'], $labels->getData());
        self::assertEquals(['3.x', 'feature'], $labels->getNormData());
        self::assertSame(EnumType::class, \get_class($labels->getConfig()->getType()->getInnerType()));
        self::assertSame(PullRequestLabelEnum::class, $labels->getConfig()->getOption('enum'));
        self::assertEquals(
            [
                'feature' => 'feature',
                'bugfix' => 'bugfix',
                'hotfix' => 'hotfix',
                '1.x' => '1.x',
                '2.x' => '2.x',
                '3.x' => '3.x',
            ],
            $labels->getConfig()->getOption('choices')
        );
    }

    /**
     * @dataProvider valid
     */
    public function testSubmitValidData(array $formData, PullRequest $model, PullRequest $expected): void
    {
        $container = self::bootKernel()->getContainer();

        /** @var FormInterface $form */
        $form = $container->get('form.factory')->create(PullRequestType::class, $model);

        $form->submit($formData);

        self::assertTrue($form->isValid());
        self::assertEquals($expected, $model);
    }

    public function valid(): Generator
    {
        $newModel = new PullRequest();
        $newExpected = new PullRequest();
        $newExpected->status = Status::OPENED();
        $newExpected->labels = ['bugfix', '1.x'];
        yield [
            ['status' => 0, 'labels' => ['bugfix', '1.x']],
            $newModel,
            $newExpected
        ];

        $updateModel = new PullRequest();
        $updateModel->status = Status::OPENED();
        $updateModel->labels = ['bugfix', '1.x'];
        $updateExpected = new PullRequest();
        $updateExpected->status = Status::CLOSED();
        $updateExpected->labels = ['bugfix', '2.x'];
        yield [
            ['status' => 2, 'labels' => ['bugfix', '2.x']],
            $updateModel,
            $updateExpected
        ];
    }

    /**
     * @dataProvider invalid
     */
    public function testSubmitInvalidData(array $formData, PullRequest $model, array $errors): void
    {
        $container = self::bootKernel()->getContainer();

        /** @var FormInterface $form */
        $form = $container->get('form.factory')->create(PullRequestType::class, $model);

        $form->submit($formData);

        self::assertFalse($form->isValid());

        foreach ($errors as $path => $message) {
            $formErrors = $form->get($path)->getErrors();
            self::assertCount(1, $formErrors);
            /** @var ConstraintViolationInterface $violation */
            $violation =  $formErrors[0]->getCause();
            self::assertSame($message, $violation->getMessage());
        }
    }

    public function invalid(): Generator
    {
        $newModel = new PullRequest();
        yield [
            ['status' => 3, 'labels' => ['bugfix', '5.x']],
            $newModel,
            ['status' => 'This value is not valid.', 'labels' => 'The choices "5.x" do not exist in the choice list.']
        ];

        $updateModel = new PullRequest();
        $updateModel->status = Status::OPENED();
        $updateModel->labels = ['bugfix', '1.x'];
        yield [
            ['status' => 3, 'labels' => ['bugfix', '5.x']],
            $updateModel,
            ['status' => 'This value is not valid.', 'labels' => 'The choices "5.x" do not exist in the choice list.']
        ];
    }
}
