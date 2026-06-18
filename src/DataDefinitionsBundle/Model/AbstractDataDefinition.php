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

use OpenDxp\Model\AbstractModel;

/**
 * @method bool isWriteable()
 * @method string getWriteTarget()
 * @method void save()
 * @method void delete()
 */
abstract class AbstractDataDefinition extends AbstractModel implements DataDefinitionInterface
{
    public int|string|null $id = null;

    public string $name = '';

    public ?string $provider = null;

    public string $class = '';

    public array $configuration = [];

    public int $creationDate = 0;

    public int $modificationDate = 0;

    public array $mapping = [];

    public ?string $runner = null;

    public bool $stopOnException = false;

    public $failureNotificationDocument = null;

    public $successNotificationDocument = null;

    public function getId(): int|string|null
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): void
    {
        $this->provider = $provider;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getCreationDate(): int
    {
        return $this->creationDate;
    }

    public function setCreationDate(int $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    public function getModificationDate(): int
    {
        return $this->modificationDate;
    }

    public function setModificationDate(int $modificationDate): void
    {
        $this->modificationDate = $modificationDate;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function setMapping(array $mapping): void
    {
        $this->mapping = $mapping;
    }

    public function getRunner(): ?string
    {
        return $this->runner;
    }

    public function setRunner(?string $runner): void
    {
        $this->runner = $runner;
    }

    public function isStopOnException(): bool
    {
        return $this->stopOnException;
    }

    public function setStopOnException(bool $stopOnException): void
    {
        $this->stopOnException = $stopOnException;
    }

    public function getFailureNotificationDocument()
    {
        return $this->failureNotificationDocument;
    }

    public function setFailureNotificationDocument($failureNotificationDocument): void
    {
        $this->failureNotificationDocument = $failureNotificationDocument;
    }

    public function getSuccessNotificationDocument()
    {
        return $this->successNotificationDocument;
    }

    public function setSuccessNotificationDocument($successNotificationDocument): void
    {
        $this->successNotificationDocument = $successNotificationDocument;
    }
}
