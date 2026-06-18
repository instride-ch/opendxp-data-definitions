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

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter;

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Tool;

class MultiHrefInterpreter implements InterpreterInterface
{
    public function interpret(InterpreterContextInterface $context): mixed
    {
        $objectClass = $context->getConfiguration()['class'];

        $class = 'OpenDxp\Model\DataObject\\' . ucfirst($objectClass);

        if (Tool::classExists($class)) {
            $class = new $class();

            if ($class instanceof Concrete) {
                $ret = $class::getById($context->getValue());

                if ($ret instanceof Concrete) {
                    return [$ret];
                }
            }
        }

        return $context->getValue();
    }
}
