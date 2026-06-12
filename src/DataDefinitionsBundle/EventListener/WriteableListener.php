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

namespace Instride\Bundle\DataDefinitionsBundle\EventListener;

use Instride\Bundle\DataDefinitionsBundle\Event\DefinitionEvent;
use Instride\Bundle\DataDefinitionsBundle\Model\ExportDefinition;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinition;
use OpenDxp\Model\Exception\ConfigWriteException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WriteableListener implements EventSubscriberInterface
{
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
