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

namespace Instride\Bundle\DataDefinitionsBundle\Loader;

use OpenDxp\Model\DataObject\AbstractObject;
use function count;
use Instride\Bundle\DataDefinitionsBundle\Context\LoaderContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportMapping;
use InvalidArgumentException;
use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Listing;

class PrimaryKeyLoader implements LoaderInterface
{
    public function load(LoaderContextInterface $context): ?Concrete
    {
        $classObject = '\OpenDxp\Model\DataObject\\' . ucfirst($context->getClass());
        $classList = '\OpenDxp\Model\DataObject\\' . ucfirst($context->getClass()) . '\Listing';

        $list = new $classList();

        if ($list instanceof Listing) {
            /**
             * @var ImportMapping[] $mapping
             */
            $mapping = $context->getDefinition()->getMapping();
            $condition = [];
            $conditionValues = [];
            foreach ($mapping as $map) {
                if ($map->getPrimaryIdentifier()) {
                    $condition[] = '`' . $map->getToColumn() . '` = ?';
                    $conditionValues[] = $context->getDataRow()[$map->getFromColumn()];
                }
            }

            if (count($condition) === 0) {
                throw new InvalidArgumentException('No primary identifier defined!');
            }

            $list->setUnpublished(true);
            $list->setCondition(implode(' AND ', $condition), $conditionValues);
            $list->setObjectTypes([
                AbstractObject::OBJECT_TYPE_VARIANT,
                AbstractObject::OBJECT_TYPE_OBJECT,
                AbstractObject::OBJECT_TYPE_FOLDER,
            ]);
            $list->load();
            $objectData = $list->getObjects();

            if (count($objectData) > 1) {
                throw new InvalidArgumentException('Object with the same primary key was found multiple times');
            }

            if (count($objectData) === 1) {
                $obj = $objectData[0];

                if ($context->getDefinition()->getForceLoadObject()) {
                    $obj = DataObject::getById($obj->getId(), ['force' => true]);

                    if (!$obj instanceof $classObject) {
                        $obj = new $classObject();
                    }
                }

                return $obj;
            }
        }

        return null;
    }
}
