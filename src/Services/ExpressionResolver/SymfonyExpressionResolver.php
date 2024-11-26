<?php

namespace App\Services\ExpressionResolver;

use App\Domain\Common\Service\ExpressionResolver;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class SymfonyExpressionResolver implements ExpressionResolver
{
    public function __construct() {}

    public function evalue(string $expression, array $variables = []): float|false
    {
        $expressionLanguage = new ExpressionLanguage();
        $value = $expressionLanguage->evaluate($this->prepare($expression), $variables);
        return \is_numeric($value) ? (float) $value : false;
    }

    public function prepare(string $expression): string
    {
        $expression = \str_replace('^', ' ** ', $expression);
        $expression = \str_replace('logPn', '\log(Pn)', $expression);
        $expression = \str_replace('Pn', 'data.Pn', $expression);
        $expression = \str_replace('E', 'data.E', $expression);
        $expression = \str_replace('F', 'data.F', $expression);
        return $expression;
    }
}
