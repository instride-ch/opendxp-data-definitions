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
 * @copyright  Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Form\Registry;

class FormTypeRegistry implements FormTypeRegistryInterface
{
    private array $types = [];

    public function add(string $type, string $formType, string $defaultType = 'default'): void
    {
        if (!isset($this->types[$type])) {
            $this->types[$type] = [];
        }

        $this->types[$type][$defaultType] = $formType;
    }

    public function get(string $type, string $defaultType = 'default'): ?string
    {
        return $this->types[$type][$defaultType] ?? null;
    }

    public function has(string $type, string $defaultType = 'default'): bool
    {
        return isset($this->types[$type][$defaultType]);
    }
}
