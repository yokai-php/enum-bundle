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
use Yokai\EnumBundle\Tests\Integration\App\Model\MyCLabsPullRequest;
use Yokai\EnumBundle\Tests\Integration\App\Model\MyCLabsStatus;
use Yokai\EnumBundle\Tests\Integration\App\Model\NativeEnumPullRequest;
use Yokai\EnumBundle\Tests\Integration\App\Model\NativeStatus;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestFormTest extends KernelTestCase
{
    /**
     * @dataProvider classes
     */
    public function testBuildForm(string $class): void
    {
        $container = self::bootKernel()->getContainer();

        $model = self::pullRequest($class, 'merged', ['3.x', 'feature']);
        $form = self::form($container, $model);

        $status = $form->get('status');
        (match ($class) {
            MyCLabsPullRequest::class => function () use ($status) {
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
            },
            NativeEnumPullRequest::class => function () use ($status) {
                self::assertEquals(NativeStatus::MERGED, $status->getData());
                self::assertEquals(NativeStatus::MERGED, $status->getNormData());
                self::assertSame(EnumType::class, \get_class($status->getConfig()->getType()->getInnerType()));
                self::assertSame(PullRequestNativeStatusEnum::class, $status->getConfig()->getOption('enum'));
                self::assertEquals(
                    ['Opened' => NativeStatus::OPENED, 'Merged' => NativeStatus::MERGED, 'Closed' => NativeStatus::CLOSED],
                    $status->getConfig()->getOption('choices')
                );
            },
        })();

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

    public function classes(): Generator
    {
        yield [MyCLabsPullRequest::class];
        yield [NativeEnumPullRequest::class];
    }

    /**
     * @dataProvider valid
     */
    public function testSubmitValidData(
        array $formData,
        MyCLabsPullRequest|NativeEnumPullRequest $model,
        MyCLabsPullRequest|NativeEnumPullRequest $expected,
    ): void {
        $container = self::bootKernel()->getContainer();

        $form = self::form($container, $model);

        $form->submit($formData);

        self::assertTrue($form->isValid());
        self::assertEquals($expected, $model);
    }

    public function valid(): Generator
    {
        foreach ($this->classes() as [$class]) {
            yield [
                ['status' => 0, 'labels' => ['bugfix', '1.x']],
                self::pullRequest($class),
                self::pullRequest($class, 'opened', ['bugfix', '1.x']),
            ];

            yield [
                ['status' => 2, 'labels' => ['bugfix', '2.x']],
                self::pullRequest($class, 'opened', ['bugfix', '1.x']),
                self::pullRequest($class, 'closed', ['bugfix', '2.x']),
            ];
        }
    }

    /**
     * @dataProvider invalid
     */
    public function testSubmitInvalidData(
        array $formData,
        MyCLabsPullRequest|NativeEnumPullRequest $model,
        array $errors,
    ): void {
        $container = self::bootKernel()->getContainer();

        $form = self::form($container, $model);

        $form->submit($formData);

        self::assertFalse($form->isValid());

        foreach ($errors as $path => $message) {
            $formErrors = $form->get($path)->getErrors();
            self::assertCount(1, $formErrors);
            /** @var ConstraintViolationInterface $violation */
            $violation = $formErrors[0]->getCause();
            self::assertSame($message, $violation->getMessage());
        }
    }

    public function invalid(): Generator
    {
        foreach ($this->classes() as [$class]) {
            yield [
                ['status' => 3, 'labels' => ['bugfix', '5.x']],
                self::pullRequest($class),
                [
                    'status' => 'The selected choice is invalid.',
                    'labels' => 'The choices "5.x" do not exist in the choice list.',
                ],
            ];

            yield [
                ['status' => 3, 'labels' => ['bugfix', '5.x']],
                self::pullRequest($class, 'opened', ['bugfix', '1.x']),
                [
                    'status' => 'The selected choice is invalid.',
                    'labels' => 'The choices "5.x" do not exist in the choice list.',
                ],
            ];
        }
    }

    private static function pullRequest(
        string $class,
        string $status = null,
        array $labels = [],
    ): MyCLabsPullRequest|NativeEnumPullRequest {
        return (match ($class) {
            MyCLabsPullRequest::class => function () use ($status, $labels) {
                $pullRequest = new MyCLabsPullRequest();
                $status && $pullRequest->status = new MyCLabsStatus($status);
                $pullRequest->labels = $labels;

                return $pullRequest;
            },
            NativeEnumPullRequest::class => function () use ($status, $labels) {
                $pullRequest = new NativeEnumPullRequest();
                $status && $pullRequest->status = NativeStatus::from($status);
                $pullRequest->labels = $labels;

                return $pullRequest;
            },
        })();
    }

    private static function form(
        ContainerInterface $container,
        MyCLabsPullRequest|NativeEnumPullRequest $model,
    ): FormInterface {
        return $container->get('form.factory')
            ->create(PullRequestType::class, $model, ['data_class' => $model::class]);
    }
}
