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

class ImportMapping extends AbstractMapping
{
    public bool $primaryIdentifier = false;

    public string $setter = '';

    public array $setterConfig = [];

    public function getPrimaryIdentifier(): bool
    {
        return $this->primaryIdentifier;
    }

    public function setPrimaryIdentifier(bool $primaryIdentifier): void
    {
        $this->primaryIdentifier = $primaryIdentifier;
    }

    public function getSetter(): string
    {
        return $this->setter;
    }

    public function setSetter(?string $setter): void
    {
        $this->setter = (string) $setter;
    }

    public function getSetterConfig(): array
    {
        return $this->setterConfig;
    }

    public function setSetterConfig(?array $setterConfig): void
    {
        $this->setterConfig = $setterConfig ?? [];
    }
}
