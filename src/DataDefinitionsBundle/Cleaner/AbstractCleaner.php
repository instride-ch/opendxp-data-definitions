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

namespace Instride\Bundle\DataDefinitionsBundle\Cleaner;

use Exception;
use Instride\Bundle\DataDefinitionsBundle\Model\DataDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\Log;
use OpenDxp\Model\DataObject\Concrete;

abstract class AbstractCleaner implements CleanerInterface
{
    abstract public function cleanup(DataDefinitionInterface $definition, array $objectIds): void;

    /**
     * @throws Exception
     */
    protected function getObjectsToClean(DataDefinitionInterface $definition, array $foundObjectIds): array
    {
        $logs = new Log\Listing();
        $logs->setCondition('definition = ?', [$definition->getId()]);
        $logs = $logs->getObjects();

        $notFound = [];

        /** @var Log $log */
        foreach ($logs as $log) {
            $found = false;

            foreach ($foundObjectIds as $objectId) {
                if ((int) $log->getO_Id() === $objectId) {
                    $found = true;

                    break;
                }
            }

            if (!$found) {
                $notFoundObject = Concrete::getById($log->getO_Id());

                if ($notFoundObject instanceof Concrete) {
                    $notFound[] = $notFoundObject;
                }
            }
        }

        $this->deleteLogs($logs);
        $this->writeNewLogs($definition, $foundObjectIds);

        return $notFound;
    }

    protected function deleteLogs(array $logs): void
    {
        foreach ($logs as $log) {
            $log->delete();
        }
    }

    protected function writeNewLogs(DataDefinitionInterface $definition, array $objectIds): void
    {
        foreach ($objectIds as $objId) {
            $log = new Log();
            $log->setO_Id((int) $objId);
            $log->setDefinition((int) $definition->getId());
            $log->save();
        }
    }
}
