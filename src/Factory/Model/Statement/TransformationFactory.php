<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement;

use webignition\BasilModels\Action\ResolvedAction;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Assertion\ResolvedAssertion;
use webignition\BasilModels\EncapsulatingStatementInterface;
use webignition\BasilModels\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;

class TransformationFactory
{
    /**
     * @param StatementInterface $statement
     *
     * @return Transformation[]
     */
    public function createTransformations(StatementInterface $statement): array
    {
        $transformations = [];

        while ($statement instanceof EncapsulatingStatementInterface) {
            $sourceStatement = $statement->getSourceStatement();
            $sourceStatementSource = $sourceStatement->getSource();

            if ($statement instanceof DerivedValueOperationAssertion) {
                $transformations[] = new Transformation(Transformation::TYPE_DERIVATION, $sourceStatementSource);
            }

            if ($statement instanceof ResolvedAction || $statement instanceof ResolvedAssertion) {
                $transformations[] = new Transformation(Transformation::TYPE_RESOLUTION, $sourceStatementSource);
            }

            $statement = $sourceStatement;
        }

        return $transformations;
    }
}
