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

use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Model\ExportDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use OpenDxp\Model\DataObject\Data\ExternalImage;

class ExternalImageInterpreter implements InterpreterInterface
{
    public function interpret(InterpreterContextInterface $context): mixed
    {
        if (($context->getDefinition() instanceof ExportDefinitionInterface) && $context->getValue(
        ) instanceof ExternalImage) {
            return $context->getValue()->getUrl();
        }

        if (($context->getDefinition() instanceof ImportDefinitionInterface) && filter_var(
            $context->getValue(),
            \FILTER_VALIDATE_URL,
        )) {
            return new ExternalImage($context->getValue());
        }

        return null;
    }
}
