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

namespace Instride\Bundle\DataDefinitionsBundle\Model;

/**
 * @method ImportDefinition\Dao getDao()
 */
class ImportDefinition extends AbstractDataDefinition implements ImportDefinitionInterface
{
    public string $loader = '';

    public string $objectPath = '';

    public ?string $cleaner = '';

    public string $key = '';

    public string $filter = '';

    public bool $renameExistingObjects = false;

    public bool $relocateExistingObjects = false;

    public bool $skipNewObjects = false;

    public bool $skipExistingObjects = false;

    public bool $createVersion = false;

    public bool $omitMandatoryCheck = false;

    public bool $forceLoadObject = false;

    public string $persister = '';

    public static function getById(int $id): self
    {
        $definitionEntry = new self();
        $dao = $definitionEntry->getDao();
        $dao->getById((string) $id);

        return $definitionEntry;
    }

    public static function getByName(string $name): self
    {
        $definitionEntry = new self();
        $dao = $definitionEntry->getDao();
        $dao->getByName($name);

        return $definitionEntry;
    }

    public function setId(int|string|null $id): void
    {
        $this->id = (int) $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLoader(): string
    {
        return $this->loader;
    }

    public function setLoader(?string $loader): void
    {
        $this->loader = (string) $loader;
    }

    public function getObjectPath(): string
    {
        return $this->objectPath;
    }

    public function setObjectPath(?string $objectPath): void
    {
        $this->objectPath = (string) $objectPath;
    }

    public function getCleaner(): string
    {
        return $this->cleaner;
    }

    public function setCleaner(?string $cleaner): void
    {
        $this->cleaner = (string) $cleaner;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = (string) $key;
    }

    public function getFilter(): string
    {
        return $this->filter;
    }

    public function setFilter(?string $filter): void
    {
        $this->filter = (string) $filter;
    }

    public function getRenameExistingObjects(): bool
    {
        return $this->renameExistingObjects;
    }

    public function setRenameExistingObjects(bool $renameExistingObjects): void
    {
        $this->renameExistingObjects = $renameExistingObjects;
    }

    public function getRelocateExistingObjects(): bool
    {
        return $this->relocateExistingObjects;
    }

    public function setRelocateExistingObjects(bool $relocateExistingObjects): void
    {
        $this->relocateExistingObjects = $relocateExistingObjects;
    }

    public function getCreateVersion(): bool
    {
        return $this->createVersion;
    }

    public function setCreateVersion(bool $createVersion): void
    {
        $this->createVersion = $createVersion;
    }

    public function getOmitMandatoryCheck(): bool
    {
        return $this->omitMandatoryCheck;
    }

    public function setOmitMandatoryCheck(bool $omitMandatoryCheck): void
    {
        $this->omitMandatoryCheck = $omitMandatoryCheck;
    }

    public function getSkipNewObjects(): bool
    {
        return $this->skipNewObjects;
    }

    public function setSkipNewObjects(bool $skipNewObjects): void
    {
        $this->skipNewObjects = $skipNewObjects;
    }

    public function getSkipExistingObjects(): bool
    {
        return $this->skipExistingObjects;
    }

    public function setSkipExistingObjects(bool $skipExistingObjects): void
    {
        $this->skipExistingObjects = $skipExistingObjects;
    }

    public function getForceLoadObject(): bool
    {
        return $this->forceLoadObject;
    }

    public function setForceLoadObject(bool $forceLoadObject): void
    {
        $this->forceLoadObject = $forceLoadObject;
    }

    public function getPersister(): string
    {
        return $this->persister;
    }

    public function setPersister(?string $persister): void
    {
        $this->persister = (string) $persister;
    }
}
