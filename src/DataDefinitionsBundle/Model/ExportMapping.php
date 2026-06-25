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

class ExportMapping extends AbstractMapping
{
    public string $getter = '';

    public array $getterConfig = [];

    public function getGetter(): string
    {
        return $this->getter;
    }

    public function setGetter(?string $getter): void
    {
        $this->getter = (string) $getter;
    }

    public function getGetterConfig(): array
    {
        return $this->getterConfig;
    }

    public function setGetterConfig(?array $getterConfig): void
    {
        $this->getterConfig = $getterConfig ?? [];
    }
}
