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

namespace Instride\Bundle\DataDefinitionsBundle\Messenger;

class ImportRowMessage
{
    private int $definitionId;

    private array $data;

    private array $params;

    public function __construct(
        int $definitionId,
        array $data,
        array $params,
    ) {
        $this->definitionId = $definitionId;
        $this->data = $data;
        $this->params = $params;
    }

    public function getDefinitionId(): int
    {
        return $this->definitionId;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
