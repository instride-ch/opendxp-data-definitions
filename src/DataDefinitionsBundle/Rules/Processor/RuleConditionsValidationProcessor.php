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
 * @copyright 2026 instride AG (https://instride.ch)
 * @license   https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Rules\Processor;

use Instride\Bundle\DataDefinitionsBundle\Registry\ServiceRegistry;
use Instride\Bundle\DataDefinitionsBundle\Rules\Condition\ImportRuleConditionCheckerInterface;
use Instride\Bundle\DataDefinitionsBundle\Rules\Model\ImportRuleInterface;

class RuleConditionsValidationProcessor
{
    private ServiceRegistry $conditionCheckerRegistry;

    public function __construct(
        ServiceRegistry $conditionCheckerRegistry,
    ) {
        $this->conditionCheckerRegistry = $conditionCheckerRegistry;
    }

    public function isValid(object $subject, ImportRuleInterface $rule, array $conditions, array $params = []): bool
    {
        foreach ($conditions as $condition) {
            $checker = $this->conditionCheckerRegistry->get($condition['type']);
            if (!$checker instanceof ImportRuleConditionCheckerInterface) {
                return false;
            }
            if (!$checker->isValid($subject, $condition, $params)) {
                return false;
            }
        }

        return true;
    }
}
