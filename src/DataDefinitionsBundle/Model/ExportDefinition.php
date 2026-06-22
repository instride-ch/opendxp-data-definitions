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

/**
 * @method ExportDefinition\Dao getDao()
 */
class ExportDefinition extends AbstractDataDefinition implements ExportDefinitionInterface
{
    public bool $enableInheritance = true;

    public ?string $fetcher = null;

    public array $fetcherConfig = [];

    public bool $fetchUnpublished = false;

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

    public function setEnableInheritance(bool $enableInheritance): void
    {
        $this->enableInheritance = $enableInheritance;
    }

    public function isEnableInheritance(): bool
    {
        return $this->enableInheritance;
    }

    public function getFetcher(): ?string
    {
        return $this->fetcher;
    }

    public function setFetcher(?string $fetcher): void
    {
        $this->fetcher = $fetcher;
    }

    public function getFetcherConfig(): array
    {
        return $this->fetcherConfig;
    }

    public function setFetcherConfig(array $fetcherConfig): void
    {
        $this->fetcherConfig = $fetcherConfig;
    }

    public function setFetchUnpublished(bool $fetchUnpublished): void
    {
        $this->fetchUnpublished = $fetchUnpublished;
    }

    public function isFetchUnpublished(): bool
    {
        return $this->fetchUnpublished;
    }
}
