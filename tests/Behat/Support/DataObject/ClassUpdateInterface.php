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
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Support\DataObject;

use OpenDxp\Model\DataObject\ClassDefinition\Data;

interface ClassUpdateInterface
{
    public function save(): bool;

    public function getProperty(string $property): mixed;

    public function setProperty(string $property, $value): void;

    public function hasField(string $fieldName): bool;

    public function getFieldDefinition(string $fieldName): ?Data;

    public function insertField(array $jsonFieldDefinition): void;

    public function insertFieldBefore(string $fieldName, array $jsonFieldDefinition): void;

    public function insertFieldAfter(string $fieldName, array $jsonFieldDefinition): void;

    public function replaceField(string $fieldName, array $jsonFieldDefinition): void;

    public function replaceFieldProperties(string $fieldName, array $keyValues): void;

    public function removeField(string $fieldName): void;

    public function insertLayoutBefore(string $fieldName, array $jsonFieldDefinition): void;

    public function insertLayoutAfter(string $fieldName, array $jsonFieldDefinition): void;

    public function replaceLayout(string $fieldName, array $jsonFieldDefinition): void;

    public function replaceLayoutProperties(string $fieldName, array $keyValues): void;

    public function removeLayout(string $fieldName): void;
}
