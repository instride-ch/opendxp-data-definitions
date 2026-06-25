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

namespace Instride\Bundle\DataDefinitionsBundle\Model\ImportMapping;

use Instride\Bundle\DataDefinitionsBundle\Model\AbstractColumn;

class ToColumn extends AbstractColumn
{
    public ?string $type = null;

    public string $label;

    public ?string $fieldtype = null;

    public ?array $config = null;

    public ?string $setter = null;

    public ?array $setterConfig = null;

    public ?string $interpreter = null;

    public ?array $interpreterConfig = null;

    public ?string $group = null;

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getFieldtype(): ?string
    {
        return $this->fieldtype;
    }

    public function setFieldtype(string $fieldtype): void
    {
        $this->fieldtype = $fieldtype;
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getSetterConfig(): ?array
    {
        return $this->setterConfig;
    }

    public function setSetterConfig(array $setterConfig): void
    {
        $this->setterConfig = $setterConfig;
    }

    public function getInterpreterConfig(): ?array
    {
        return $this->interpreterConfig;
    }

    public function setInterpreterConfig(array $interpreterConfig): void
    {
        $this->interpreterConfig = $interpreterConfig;
    }

    public function getSetter(): ?string
    {
        return $this->setter;
    }

    public function setSetter(string $setter): void
    {
        $this->setter = $setter;
    }

    public function getInterpreter(): ?string
    {
        return $this->interpreter;
    }

    public function setInterpreter(string $interpreter): void
    {
        $this->interpreter = $interpreter;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(string $group): void
    {
        $this->group = $group;
    }
}
