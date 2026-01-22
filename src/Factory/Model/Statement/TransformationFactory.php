<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement;

use webignition\BasilModels\Model\Statement\Action\ResolvedAction;
use webignition\BasilModels\Model\Statement\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Model\Statement\Assertion\ResolvedAssertion;
use webignition\BasilModels\Model\Statement\EncapsulatingStatementInterface;
use webignition\BasilModels\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;

class TransformationFactory
{
    /**
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
