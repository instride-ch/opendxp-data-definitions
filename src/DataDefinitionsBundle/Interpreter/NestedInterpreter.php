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
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @copyright  Modification Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter;

use Instride\Bundle\DataDefinitionsBundle\Context\ContextFactoryInterface;
use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Registry\ServiceRegistry;
use Webmozart\Assert\Assert;

final class NestedInterpreter implements InterpreterInterface
{
    public function __construct(
        private ServiceRegistry $interpreterRegistry,
        private ContextFactoryInterface $contextFactory,
    ) {
    }

    public function interpret(InterpreterContextInterface $context): mixed
    {
        Assert::keyExists($context->getConfiguration(), 'interpreters');
        Assert::isArray($context->getConfiguration()['interpreters'], 'Interpreter Config needs to be array');

        $value = $context->getValue();

        foreach ($context->getConfiguration()['interpreters'] as $interpreter) {
            $interpreterObject = $this->interpreterRegistry->get($interpreter['type']);
            $newContext = $this->contextFactory->createInterpreterContext(
                $context->getDefinition(),
                $context->getParams(),
                $interpreter['interpreterConfig'],
                $context->getDataRow(),
                $context->getDataSet(),
                $context->getObject(),
                $value,
                $context->getMapping(),
            );

            $value = $interpreterObject->interpret($newContext);
        }

        return $value;
    }
}
