<?php

declare(strict_types=1);

/*
 * This source file is available under two different licenses:
 *  - GNU General Public License version 3 (GPLv3)
 *  - Data Definitions Commercial License (DDCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh) in combination with instride AG (https://instride.ch)
 * @license    GPLv3 and DDCL
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
}
