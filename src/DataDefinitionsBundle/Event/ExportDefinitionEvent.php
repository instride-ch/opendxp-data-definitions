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

namespace Instride\Bundle\DataDefinitionsBundle\Event;

use Instride\Bundle\DataDefinitionsBundle\Model\DataDefinitionInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ExportDefinitionEvent extends Event implements DefinitionEventInterface
{
    protected DataDefinitionInterface $definition;

    protected $subject;

    protected array $params = [];

    public function __construct(
        DataDefinitionInterface $definition,
                                $subject = null,
        array                   $params = [],
    )
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->params = $params;
    }

    public function getDefinition(): DataDefinitionInterface
    {
        return $this->definition;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
