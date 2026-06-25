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

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter\Ecommerce;

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Interpreter\InterpreterInterface;

final class PriceInterpreter implements InterpreterInterface
{
    public function interpret(InterpreterContextInterface $context): int
    {
        $inputIsFloat = $context->getConfiguration()['isFloat'];
        $value = $context->getValue();

        if (\is_string($value)) {
            $value = str_replace(',', '.', $value);
            $value = (float) $value;
        }

        if ($inputIsFloat) {
            $value = (int) round(round($value, 2) * 100, 0);
        }

        return (int) $value;
    }
}
