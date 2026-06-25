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

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ExportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
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
