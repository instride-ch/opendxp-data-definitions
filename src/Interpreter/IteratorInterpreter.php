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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Interpreter;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\ContextFactoryInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Registry\ServiceRegistry;
use Webmozart\Assert\Assert;

final class IteratorInterpreter implements InterpreterInterface
{
    public function __construct(
        private ServiceRegistry $interpreterRegistry,
        private ContextFactoryInterface $contextFactory,
    ) {
    }

    public function interpret(InterpreterContextInterface $context): mixed
    {
        if (null === $context->getValue()) {
            return [];
        }
        Assert::isArray($context->getValue(), 'IteratorInterpreter can only be used with array values');

        $interpreter = $context->getConfiguration()['interpreter'];
        $interpreterObject = $this->interpreterRegistry->get($interpreter['type']);

        $value = $context->getValue();
        $result = [];

        foreach ($value as $val) {
            $context = $this->contextFactory->createInterpreterContext(
                $context->getDefinition(),
                $context->getParams(),
                $interpreter['interpreterConfig'],
                $context->getDataRow(),
                $context->getDataSet(),
                $context->getObject(),
                $val,
                $context->getMapping(),
            );

            $result[] = $interpreterObject->interpret($context);
        }

        return $result;
    }
}
