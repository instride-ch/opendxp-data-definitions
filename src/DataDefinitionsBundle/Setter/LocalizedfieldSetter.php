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

namespace Instride\Bundle\DataDefinitionsBundle\Setter;

use Instride\Bundle\DataDefinitionsBundle\Context\GetterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Context\SetterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Getter\GetterInterface;

class LocalizedfieldSetter implements SetterInterface, GetterInterface
{
    public function set(SetterContextInterface $context): void
    {
        $config = $context->getMapping()->getSetterConfig();

        $setter = explode('~', $context->getMapping()->getToColumn());
        $setter = sprintf('set%s', ucfirst($setter[0]));

        if (method_exists($context->getObject(), $setter)) {
            $context->getObject()->$setter($context->getValue(), $config['language']);
        }
    }

    public function get(GetterContextInterface $context)
    {
        $config = $context->getMapping()->getGetterConfig();

        $getter = explode('~', $context->getMapping()->getFromColumn());
        $getter = sprintf('get%s', ucfirst($getter[0]));

        if (method_exists($context->getObject(), $getter)) {
            return $context->getObject()->$getter($config['language']);
        }

        return null;
    }
}
