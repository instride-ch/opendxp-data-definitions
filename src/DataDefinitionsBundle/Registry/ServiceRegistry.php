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

namespace Instride\Bundle\DataDefinitionsBundle\Registry;

use InvalidArgumentException;

class ServiceRegistry
{
    private string $interface;

    private array $services = [];

    public function __construct(
        string $interface,
    ) {
        $this->interface = $interface;
    }

    public function register(string $type, object $service): void
    {
        if (!$service instanceof $this->interface) {
            throw new InvalidArgumentException(sprintf(
                'Service must implement %s, %s given',
                $this->interface,
                get_class($service),
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
