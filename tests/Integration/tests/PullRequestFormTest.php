<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration;

use Generator;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestLabelEnum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestStatusEnum;
use Yokai\EnumBundle\Tests\Integration\App\Form\PullRequestType;
use Yokai\EnumBundle\Tests\Integration\App\Kernel;
use Yokai\EnumBundle\Tests\Integration\App\Model\PullRequestUsingAnnotations;
use Yokai\EnumBundle\Tests\Integration\App\Model\PullRequestUsingAttributes;
use Yokai\EnumBundle\Tests\Integration\App\Model\Status;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestFormTest extends KernelTestCase
{
    public function testBuildForm(): void
    {
        $container = self::bootKernel()->getContainer();

        $model = self::pullRequest();
        $model->status = Status::MERGED();
        $model->labels = ['3.x', 'feature'];

        $form = self::form($container, $model);

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
    public function testSubmitValidData(array $formData, $model, $expected): void
    {
        $container = self::bootKernel()->getContainer();

        $form = self::form($container, $model);

        $form->submit($formData);

        self::assertTrue($form->isValid());
        self::assertEquals($expected, $model);
    }

    public function valid(): Generator
    {
        $newModel = self::pullRequest();
        $newExpected = self::pullRequest();
        $newExpected->status = Status::OPENED();
        $newExpected->labels = ['bugfix', '1.x'];
        yield [
            ['status' => 0, 'labels' => ['bugfix', '1.x']],
            $newModel,
            $newExpected
        ];

        $updateModel = self::pullRequest();
        $updateModel->status = Status::OPENED();
        $updateModel->labels = ['bugfix', '1.x'];
        $updateExpected = self::pullRequest();
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
    public function testSubmitInvalidData(array $formData, $model, array $errors): void
    {
        $container = self::bootKernel()->getContainer();

        $form = self::form($container, $model);

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
        $message = 'This value is not valid.';
        if (Kernel::MAJOR_VERSION >= 6) {
            $message = 'The selected choice is invalid.';
        }

        $newModel = self::pullRequest();
        yield [
            ['status' => 3, 'labels' => ['bugfix', '5.x']],
            $newModel,
            ['status' => $message, 'labels' => 'The choices "5.x" do not exist in the choice list.']
        ];

        $updateModel = self::pullRequest();
        $updateModel->status = Status::OPENED();
        $updateModel->labels = ['bugfix', '1.x'];
        yield [
            ['status' => 3, 'labels' => ['bugfix', '5.x']],
            $updateModel,
            ['status' => $message, 'labels' => 'The choices "5.x" do not exist in the choice list.']
        ];
    }

    /**
     * @return PullRequestUsingAnnotations|PullRequestUsingAttributes
     */
    private static function pullRequest()
    {
        if (\PHP_VERSION_ID < 80000 || Kernel::VERSION_ID < 50200) {
            return new PullRequestUsingAnnotations();
        }

        return new PullRequestUsingAttributes();
    }

    private static function form(ContainerInterface $container, $model): FormInterface
    {
        if (\PHP_VERSION_ID < 80000 || Kernel::VERSION_ID < 50200) {
            $class = PullRequestUsingAnnotations::class;
        } else {
            $class = PullRequestUsingAttributes::class;
        }

        return $container->get('form.factory')
            ->create(PullRequestType::class, $model, ['data_class' => $class]);
    }
}
