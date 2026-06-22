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

namespace Instride\Bundle\DataDefinitionsBundle\Model\Log;

use Exception;
use function in_array;
use Instride\Bundle\DataDefinitionsBundle\Model\Log;
use OpenDxp\Model;
use OpenDxp\Model\Paginator\PaginateListingInterface;

class Listing extends Model\Listing\AbstractListing implements PaginateListingInterface
{
    public ?array $data;

    public string $locale;

    public array $validOrderKeys = ['id'];

    public function isValidOrderKey(string $key): bool
    {
        return in_array($key, $this->validOrderKeys, true);
    }

    /**
     * @throws Exception
     */
    public function getObjects(): ?array
    {
        if (null === $this->data) {
            $this->load();
        }

        return $this->data;
    }

    public function setObjects(array $data): void
    {
        $this->data = $data;
    }

    /** Methods for AdapterInterface */

    /**
     * @throws Exception
     */
    public function count(): int
    {
        return $this->getTotalCount();
    }

    /**
     * @param int $offset
     * @param int $itemCountPerPage
     *
     * @throws Exception
     */
    public function getItems(int $offset, int $itemCountPerPage): array
    {
        $this->setOffset($offset);
        $this->setLimit($itemCountPerPage);

        return $this->load();
    }

    /**
     * Get Paginator Adapter
     *
     * @return $this
     */
    public function getPaginatorAdapter(): Listing
    {
        return $this;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Methods for Iterator
     */

    /**
     * @throws Exception
     */
    public function rewind(): void
    {
        $this->getData();
        reset($this->data);
    }

    /**
     * @throws Exception
     */
    public function current(): mixed
    {
        $this->getData();

        return current($this->data);
    }

    /**
     * @throws Exception
     */
    public function key(): int|string|null
    {
        $this->getData();

        return key($this->data);
    }

    /**
     * @throws Exception
     */
    public function next(): void
    {
        $this->getData();

        next($this->data);
    }

    /**
     * @throws Exception
     */
    public function valid(): bool
    {
        $this->getData();

        return $this->current() !== false;
    }
}
