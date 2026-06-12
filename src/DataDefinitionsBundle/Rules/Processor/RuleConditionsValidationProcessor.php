<?php

declare(strict_types=1);

/*
 * This source file is available under two different licenses:
 *  - GNU General Public License version 3 (GPLv3)
 *  - Data Definitions Commercial License (DDCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh) in combination with instride AG (https://instride.ch)
 * @license    GPLv3 and DDCL
 */

namespace Instride\Bundle\DataDefinitionsBundle\Rules\Processor;

use Instride\Bundle\DataDefinitionsBundle\Rules\Condition\ImportRuleConditionCheckerInterface;
use Instride\Bundle\DataDefinitionsBundle\Rules\Model\ImportRuleInterface;

class RuleConditionsValidationProcessor
{
    private ImportRuleConditionCheckerInterface $conditionChecker;

    public function __construct(
        ImportRuleConditionCheckerInterface $conditionChecker,
    ) {
        $this->conditionChecker = $conditionChecker;
    }

    public function isValid(object $subject, ImportRuleInterface $rule, array $conditions, array $params = []): bool
    {
        foreach ($conditions as $condition) {
            if (!$this->conditionChecker->isValid($subject, $condition, $params)) {
                return false;
            }
        }

        return true;
    }
}
