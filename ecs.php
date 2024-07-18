<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests/Integration/src',
        __DIR__ . '/tests/Integration/tests',
        __DIR__ . '/tests/Unit',
    ]);

    $ecsConfig->sets([
        SetList::ARRAY,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::COMMENTS,
        SetList::STRICT,
        SetList::PSR_12,
    ]);

    $ecsConfig->skip([
        /* Do not force array on multiple lines : ['foo' => $foo, 'bar' => $bar] */
        ArrayOpenerAndCloserNewlineFixer::class,
        ArrayListItemNewlineFixer::class,
        StandaloneLineInMultilineArrayFixer::class,
    ]);

    $ecsConfig->ruleWithConfiguration(YodaStyleFixer::class, [
        'equal' => false,
        'identical' => false,
        'less_and_greater' => false,
    ]);
    $ecsConfig->ruleWithConfiguration(LineLengthFixer::class, [
        LineLengthFixer::INLINE_SHORT_LINES => false,
    ]);
    $ecsConfig->ruleWithConfiguration(ForbiddenFunctionsSniff::class, [
        'forbiddenFunctions' => ['dump' => null, 'dd' => null, 'var_dump' => null, 'die' => null],
    ]);
    $ecsConfig->ruleWithConfiguration(FunctionDeclarationFixer::class, [
        'closure_fn_spacing' => 'none',
    ]);
    $ecsConfig->ruleWithConfiguration(NativeFunctionInvocationFixer::class, [
        'scope' => 'namespaced',
        'include' => ['@all'],
    ]);
};
