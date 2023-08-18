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
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestMyCLabsStatusEnum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestNativeStatusEnum;
use Yokai\EnumBundle\Tests\Integration\App\Form\PullRequestType;
use Yokai\EnumBundle\Tests\Integration\App\Kernel;
use Yokai\EnumBundle\Tests\Integration\App\Model\NativeStatus;
use Yokai\EnumBundle\Tests\Integration\App\Model\PullRequestPhp7;
use Yokai\EnumBundle\Tests\Integration\App\Model\PullRequestPhp80;
use Yokai\EnumBundle\Tests\Integration\App\Model\MyCLabsStatus;
use Yokai\EnumBundle\Tests\Integration\App\Model\PullRequestPhp81;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestFormTest extends KernelTestCase
{
    public function testBuildForm(): void
    {
        $container = self::bootKernel()->getContainer();

        $model = self::pullRequest('merged', ['3.x', 'feature']);
        $form = self::form($container, $model);

        $status = $form->get('status');
        if (\PHP_VERSION_ID < 80100) {
            self::assertEquals(MyCLabsStatus::MERGED(), $status->getData());
            self::assertEquals(MyCLabsStatus::MERGED(), $status->getNormData());
            self::assertSame(EnumType::class, \get_class($status->getConfig()->getType()->getInnerType()));
            self::assertSame(PullRequestMyCLabsStatusEnum::class, $status->getConfig()->getOption('enum'));
            self::assertEquals(
                [
                    'Opened' => MyCLabsStatus::OPENED(),
                    'Merged' => MyCLabsStatus::MERGED(),
                    'Closed' => MyCLabsStatus::CLOSED(),
                ],
                $status->getConfig()->getOption('choices')
            );
        } else {
            self::assertEquals(NativeStatus::MERGED, $status->getData());
            self::assertEquals(NativeStatus::MERGED, $status->getNormData());
            self::assertSame(EnumType::class, \get_class($status->getConfig()->getType()->getInnerType()));
            self::assertSame(PullRequestNativeStatusEnum::class, $status->getConfig()->getOption('enum'));
            self::assertEquals(
                ['Opened' => NativeStatus::OPENED, 'Merged' => NativeStatus::MERGED, 'Closed' => NativeStatus::CLOSED],
                $status->getConfig()->getOption('choices')
            );
        }

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
        yield [
            ['status' => 0, 'labels' => ['bugfix', '1.x']],
            self::pullRequest(),
            self::pullRequest('opened', ['bugfix', '1.x'])
        ];

        yield [
            ['status' => 2, 'labels' => ['bugfix', '2.x']],
            self::pullRequest('opened', ['bugfix', '1.x']),
            self::pullRequest('closed', ['bugfix', '2.x'])
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

        yield [
            ['status' => 3, 'labels' => ['bugfix', '5.x']],
            self::pullRequest(),
            ['status' => $message, 'labels' => 'The choices "5.x" do not exist in the choice list.']
        ];

        yield [
            ['status' => 3, 'labels' => ['bugfix', '5.x']],
            self::pullRequest('opened', ['bugfix', '1.x']),
            ['status' => $message, 'labels' => 'The choices "5.x" do not exist in the choice list.']
        ];
    }

    /**
     * @return PullRequestPhp7|PullRequestPhp80|PullRequestPhp81
     */
    private static function pullRequest(string $status = null, array $labels = [])
    {
        if (Kernel::VERSION_ID < 50200) {
            $pullRequest = new PullRequestPhp7();
            $status && $pullRequest->status = new MyCLabsStatus($status);
            $pullRequest->labels = $labels;

            return $pullRequest;
        }
        if (\PHP_VERSION_ID < 80000) {
            $pullRequest = new PullRequestPhp7();
            $status && $pullRequest->status = new MyCLabsStatus($status);
            $pullRequest->labels = $labels;

            return $pullRequest;
        }
        if (\PHP_VERSION_ID < 80100) {
            $pullRequest = new PullRequestPhp80();
            $status && $pullRequest->status = new MyCLabsStatus($status);
            $pullRequest->labels = $labels;

            return $pullRequest;
        }

        $pullRequest = new PullRequestPhp81();
        $status && $pullRequest->status = NativeStatus::from($status);
        $pullRequest->labels = $labels;

        return $pullRequest;
    }

    private static function form(ContainerInterface $container, $model): FormInterface
    {
        $class = get_class(self::pullRequest());

        return $container->get('form.factory')
            ->create(PullRequestType::class, $model, ['data_class' => $class]);
    }
}
