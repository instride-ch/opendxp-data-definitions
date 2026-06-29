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

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\InterpreterContextInterface;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\Element\Service;
use OpenDxp\Tool;

class HrefInterpreter implements InterpreterInterface
{
    public function interpret(InterpreterContextInterface $context): mixed
    {
        $type = $context->getConfiguration()['type'] ?: 'object';
        $objectClass = $context->getConfiguration()['class'];

        if (!$context->getValue()) {
            return null;
        }

        if ($type === 'object' && $objectClass) {
            $class = 'OpenDxp\Model\DataObject\\' . $objectClass;

            if (!Tool::classExists($class)) {
                $class = 'OpenDxp\Model\DataObject\\' . ucfirst($objectClass);
            }

            if (Tool::classExists($class)) {
                $class = new $class();

                if ($class instanceof Concrete) {
                    $ret = $class::getById($context->getValue());

                    if ($ret instanceof Concrete) {
                        return $ret;
                    }
                }
            }
        } else {
            return Service::getElementById($type, $context->getValue());
        }

        return null;
    }
}
