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

namespace Instride\Bundle\DataDefinitionsBundle\Repository;

use Instride\Bundle\DataDefinitionsBundle\Model\DataDefinitionInterface;

class DefinitionRepository
{
    private string $modelClass;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function find($id): ?DataDefinitionInterface
    {
        if (!$id) {
            return null;
        }

        $class = $this->modelClass;
        $definition = new $class();
        $definition->getDao()->getById((string) $id);

        return $definition;
    }

    public function findByName(string $name): ?DataDefinitionInterface
    {
        $class = $this->modelClass;
        $definitionEntry = new $class();
        $definitionEntry->getDao()->getByName($name);

        return $definitionEntry;
    }

    public function getAll(): array
    {
        $listingClass = str_replace('\\Model\\', '\\Model\\', $this->modelClass) . '\\Listing';
        if (!class_exists($listingClass)) {
            return [];
        }

        $listing = new $listingClass();

        return $listing->getObjects() ?? [];
    }

    public function findAll(): array
    {
        return $this->getAll();
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }
}
