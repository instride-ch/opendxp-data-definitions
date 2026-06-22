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

namespace Instride\Bundle\DataDefinitionsBundle\Model;

interface ImportDefinitionInterface extends DataDefinitionInterface
{
    public function getLoader(): string;

    public function setLoader(string $loader): void;

    public function getObjectPath(): string;

    public function setObjectPath(string $objectPath): void;

    public function getCleaner(): string;

    public function setCleaner(string $cleaner): void;

    public function getKey(): string;

    public function setKey(string $key): void;

    public function getFilter(): string;

    public function setFilter(string $filter): void;

    public function getRenameExistingObjects(): bool;

    public function setRenameExistingObjects(bool $renameExistingObjects): void;

    public function getRelocateExistingObjects(): bool;

    public function setRelocateExistingObjects(bool $relocateExistingObjects): void;

    public function getOmitMandatoryCheck(): bool;

    public function setOmitMandatoryCheck(bool $omitMandatoryCheck): void;

    public function getSkipNewObjects(): bool;

    public function setSkipNewObjects(bool $skipNewObjects): void;

    public function getSkipExistingObjects(): bool;

    public function setSkipExistingObjects(bool $skipExistingObjects): void;

    public function getForceLoadObject(): bool;

    public function setForceLoadObject(bool $forceLoadObject): void;

    public function getCreateVersion(): bool;

    public function setCreateVersion(bool $createVersion): void;

    public function getPersister(): string;

    public function setPersister(string $persister): void;
}
