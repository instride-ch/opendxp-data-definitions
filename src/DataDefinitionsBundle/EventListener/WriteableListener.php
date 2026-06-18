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

namespace Instride\Bundle\DataDefinitionsBundle\EventListener;

use Instride\Bundle\DataDefinitionsBundle\Event\DefinitionEvent;
use Instride\Bundle\DataDefinitionsBundle\Model\ExportDefinition;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinition;
use OpenDxp\Model\Exception\ConfigWriteException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WriteableListener implements EventSubscriberInterface
{
    /**
     * @throws ConfigWriteException
     */
    public function definitionIsWritable(DefinitionEvent $event): void
    {
        $subject = $event->getSubject();

        if ($subject instanceof ImportDefinition && !$subject->isWriteable()) {
            throw new ConfigWriteException();
        }

        if ($subject instanceof ExportDefinition && !$subject->isWriteable()) {
            throw new ConfigWriteException();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DefinitionEvent::PRE_SAVE => 'definitionIsWritable',
        ];
    }
}
