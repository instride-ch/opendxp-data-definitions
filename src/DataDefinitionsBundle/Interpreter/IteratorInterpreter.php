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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Interpreter;

use OpenDxp\Ecommerce\Component\Registry\ServiceRegistryInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Context\ContextFactoryInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Webmozart\Assert\Assert;

final class IteratorInterpreter implements InterpreterInterface
{
    public function __construct(
        private ServiceRegistryInterface $interpreterRegistry,
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
