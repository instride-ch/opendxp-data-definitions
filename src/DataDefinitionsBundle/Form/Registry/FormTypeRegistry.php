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

namespace Instride\Bundle\DataDefinitionsBundle\Form\Registry;

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
