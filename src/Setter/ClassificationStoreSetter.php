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

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\GetterContextInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\SetterContextInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Getter\GetterInterface;
use OpenDxp\Model\DataObject\Classificationstore;

class ClassificationStoreSetter implements SetterInterface, GetterInterface
{
    public function set(SetterContextInterface $context): void
    {
        $mapConfig = $context->getMapping()->getSetterConfig();
        $fieldName = $mapConfig['field'];
        $keyConfig = (int) $mapConfig['keyConfig'];
        $groupConfig = (int) $mapConfig['groupConfig'];

        $classificationStoreGetter = sprintf('get%s', ucfirst($fieldName));

        if (method_exists($context->getObject(), $classificationStoreGetter)) {
            $classificationStore = $context->getObject()->$classificationStoreGetter();

            if ($classificationStore instanceof Classificationstore) {
                $groups = $classificationStore->getActiveGroups();

                if (!($groups[$groupConfig] ?? false)) {
                    $groups[$groupConfig] = true;
                    $classificationStore->setActiveGroups($groups);
                }

                $classificationStore->setLocalizedKeyValue($groupConfig, $keyConfig, $context->getValue());
            }
        }
    }

    public function get(GetterContextInterface $context)
    {
        $mapConfig = $context->getMapping()->getGetterConfig();
        $fieldName = $mapConfig['field'];
        $keyConfig = (int) $mapConfig['keyConfig'];
        $groupConfig = (int) $mapConfig['groupConfig'];

        $classificationStoreGetter = sprintf('get%s', ucfirst($fieldName));

        if (method_exists($context->getObject(), $classificationStoreGetter)) {
            $classificationStore = $context->getObject()->$classificationStoreGetter();

            if ($classificationStore instanceof Classificationstore) {
                $groups = $classificationStore->getActiveGroups();

                if (!($groups[$groupConfig] ?? false)) {
                    $groups[$groupConfig] = true;
                    $classificationStore->setActiveGroups($groups);
                }

                return $classificationStore->getLocalizedKeyValue($groupConfig, $keyConfig);
            }
        }

        return null;
    }
}
