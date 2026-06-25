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

namespace Instride\Bundle\DataDefinitionsBundle\Setter;

use Instride\Bundle\DataDefinitionsBundle\Context\GetterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Getter\GetterInterface;
use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\Classificationstore;
use OpenDxp\Tool;

class ClassificationStoreFieldGetter implements GetterInterface
{
    public function get(GetterContextInterface $context)
    {
        $classificationStoreGetter = sprintf('get%s', ucfirst($context->getMapping()->getFromColumn()));

        if (method_exists($context->getObject(), $classificationStoreGetter)) {
            $classificationStore = $context->getObject()->$classificationStoreGetter();

            if ($classificationStore instanceof Classificationstore) {
                $groups = $classificationStore->getActiveGroups();
                $values = [];

                foreach ($groups as $groupId => $groupIsActive) {
                    if (!$groupIsActive) {
                        continue;
                    }

                    $group = DataObject\Classificationstore\GroupConfig::getById($groupId);
                    $groupRelations = $group->getRelations();

                    foreach ($groupRelations as $keyRelation) {
                        $keyConfig = DataObject\Classificationstore\KeyConfig::getById($keyRelation->getKeyId());

                        foreach (Tool::getValidLanguages() as $language) {
                            $value = $classificationStore->getLocalizedKeyValue(
                                $groupId,
                                $keyConfig->getId(),
                                $language,
                            );

                            if (null === $value) {
                                continue;
                            }

                            $values[sprintf('%s-%s-%s', $groupId, $keyRelation->getKeyId(), $language)] = $value;
                        }
                    }
                }

                return $values;
            }
        }

        return null;
    }
}
