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
use Instride\Bundle\DataDefinitionsBundle\Rules\Action\ImportRuleProcessorInterface;
use Instride\Bundle\DataDefinitionsBundle\Rules\Model\ImportRuleInterface;
use OpenDxp\Model\DataObject\Concrete;

class RuleApplier implements RuleApplierInterface
{
    private ServiceRegistry $actionServiceRegistry;

    public function __construct(
        ServiceRegistry $actionServiceRegistry,
    ) {
        $this->actionServiceRegistry = $actionServiceRegistry;
    }

    public function applyRule(ImportRuleInterface $rule, Concrete $concrete, $value, array $params)
    {
        foreach ($rule->getActions() as $action) {
            $processor = $this->actionServiceRegistry->get($action->getType());

            if ($processor instanceof ImportRuleProcessorInterface) {
                $value = $processor->apply($rule, $concrete, $value, $action->getConfiguration(), $params);
            }
        }

        return $value;
    }
}
