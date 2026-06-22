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

namespace Instride\Bundle\DataDefinitionsBundle\Model;

abstract class AbstractMapping implements MappingInterface
{
    public string $fromColumn = '';

    public string $toColumn = '';

    public string $interpreter = '';

    public array $interpreterConfig = [];

    public function setValues(array $values): void
    {
        foreach ($values as $key => $value) {
            if ($key === 'o_type') {
                continue;
            }

            $setter = sprintf('set%s', ucfirst($key));

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    public function getToColumn(): string
    {
        return $this->toColumn;
    }

    public function setToColumn(?string $toColumn): void
    {
        $this->toColumn = $toColumn ?? '';
    }

    public function getFromColumn(): string
    {
        return $this->fromColumn;
    }

    public function setFromColumn(?string $fromColumn): void
    {
        $this->fromColumn = $fromColumn ?? '';
    }

    public function getInterpreter(): string
    {
        return $this->interpreter;
    }

    public function setInterpreter(?string $interpreter): void
    {
        $this->interpreter = $interpreter ?? '';
    }

    public function getInterpreterConfig(): array
    {
        return $this->interpreterConfig;
    }

    public function setInterpreterConfig(?array $interpreterConfig): void
    {
        $this->interpreterConfig = $interpreterConfig ?? [];
    }
}
