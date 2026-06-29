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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Setter;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\SetterContextInterface;
use OpenDxp\Model\DataObject;

class KeySetter implements SetterInterface
{
    public function set(SetterContextInterface $context): void
    {
        $setter = explode('~', $context->getMapping()->getToColumn());
        $setter = preg_replace('/^o_/', '', $setter[0]);
        $setter = sprintf('set%s', ucfirst($setter));

        if (method_exists($context->getObject(), $setter)) {
            $context->getObject()->$setter(DataObject\Service::getValidKey($context->getValue(), 'object'));
        }
    }
}
