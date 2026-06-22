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
 * @license   https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Rules\Condition;

use Instride\Bundle\DataDefinitionsBundle\Rules\Model\ImportRuleInterface;
use OpenDxp\Model\DataObject\Concrete;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionConditionChecker extends AbstractConditionChecker
{
    protected ExpressionLanguage $expressionLanguage;

    protected ContainerInterface $container;

    public function __construct(
        ExpressionLanguage $expressionLanguage,
        ContainerInterface $container,
    ) {
        $this->expressionLanguage = $expressionLanguage;
        $this->container = $container;
    }

    public function isImportRuleValid(
        ImportRuleInterface $subject,
        Concrete $concrete,
        array $params,
        array $configuration,
    ): bool {
        $expression = $configuration['expression'];

        return $this->expressionLanguage->evaluate(
            $expression,
            array_merge($params, ['container' => $this->container]),
        );
    }
}
