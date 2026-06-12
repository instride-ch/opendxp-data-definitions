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

namespace Instride\Bundle\DataDefinitionsBundle\Registry;

use InvalidArgumentException;

class ServiceRegistry
{
    private string $interface;

    private string $context;

    private array $services = [];

    public function __construct(string $interface, string $context)
    {
        $this->interface = $interface;
        $this->context = $context;
    }

    public function register(string $type, object $service): void
    {
        if (!$service instanceof $this->interface) {
            throw new InvalidArgumentException(sprintf(
                'Service must implement %s, %s given',
                $this->interface,
                get_class($service)
            ));
        }

        $this->services[$type] = $service;
    }

    public function get(string $type): ?object
    {
        return $this->services[$type] ?? null;
    }

    public function has(string $type): bool
    {
        return isset($this->services[$type]);
    }

    public function all(): array
    {
        return $this->services;
    }
}
