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

interface DataDefinitionInterface
{
    public function getId(): int|string|null;

    public function setId(int|string|null $id): void;

    public function getProvider(): ?string;

    public function setProvider(?string $provider): void;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;

    public function getClass(): string;

    public function setClass(string $class): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getMapping(): array;

    public function setMapping(array $mapping): void;

    public function getCreationDate(): int;

    public function setCreationDate(int $creationDate): void;

    public function getModificationDate(): int;

    public function setModificationDate(int $modificationDate): void;

    public function getRunner(): ?string;

    public function setRunner(?string $runner): void;

    public function isStopOnException(): bool;

    public function setStopOnException(bool $stopOnException): void;

    public function getFailureNotificationDocument();

    public function setFailureNotificationDocument($failureNotificationDocument): void;

    public function getSuccessNotificationDocument();

    public function setSuccessNotificationDocument($successNotificationDocument): void;
}
