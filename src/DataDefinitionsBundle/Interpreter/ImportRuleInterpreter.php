<?php

declare(strict_types=1);

/**
 * OpenDXP Data Definitions.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh) in combination with instride AG (https://instride.ch)
 * @copyright  Modification Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter;

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Rules\Model\ImportRule;
use Instride\Bundle\DataDefinitionsBundle\Rules\Processor\ImportRuleValidationProcessorInterface;
use Instride\Bundle\DataDefinitionsBundle\Rules\Processor\RuleApplierInterface;
use OpenDxp\Ecommerce\Component\Rule\Model\Action;
use OpenDxp\Ecommerce\Component\Rule\Model\Condition;

class ImportRuleInterpreter implements InterpreterInterface
{
    protected RuleApplierInterface $ruleProcessor;

    protected ImportRuleValidationProcessorInterface $ruleValidationProcessor;

    public function __construct(
        ImportRuleValidationProcessorInterface $ruleValidationProcessor,
        RuleApplierInterface $ruleProcessor,
    ) {
        $this->ruleValidationProcessor = $ruleValidationProcessor;
        $this->ruleProcessor = $ruleProcessor;
    }

    public function interpret(InterpreterContextInterface $context): mixed
    {
        $rules = $context->getConfiguration()['rules'];
        $ruleObjects = [];

        foreach ($rules as $rule) {
            $ruleObject = new ImportRule();
            $ruleObject->setName($rule['name']);
            $ruleObject->setActive($rule['active']);

            foreach ($rule['conditions'] as $condition) {
                $conditionObject = new Condition();
                $conditionObject->setType($condition['type']);
                $conditionObject->setConfiguration($condition['configuration']);

                $ruleObject->addCondition($conditionObject);
            }

            foreach ($rule['actions'] as $action) {
                $actionObject = new Action();
                $actionObject->setType($action['type']);
                $actionObject->setConfiguration($action['configuration']);

                $ruleObject->addAction($actionObject);
            }

            $ruleObjects[] = $ruleObject;
        }

        $params = [
            'value' => $context->getValue(),
            'object' => $context->getObject(),
            'map' => $context->getMapping(),
            'data' => $context->getDataRow(),
            'params' => $context->getParams(),
            'data_set' => $context->getDataSet(),
        ];

        $value = $context->getValue();

        foreach ($ruleObjects as $rule) {
            if ($this->ruleValidationProcessor->isImportRuleValid(
                $context->getDefinition(),
                $context->getObject(),
                $rule,
                $params,
            )) {
                $value = $this->ruleProcessor->applyRule(
                    $rule,
                    $context->getObject(),
                    $value,
                    $params,
                );
            }
        }

        return $value;
    }
}
