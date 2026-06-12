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

namespace Instride\Bundle\DataDefinitionsBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class DefinitionEvent extends Event implements DefinitionEventInterface
{
    public const PRE_SAVE = 'data_definitions.definition.pre_save';
    public const POST_SAVE = 'data_definitions.definition.post_save';

    private object $subject;

    public function __construct(object $subject)
    {
        $this->subject = $subject;
    }

    public function getDefinition()
    {
        return $this->subject;
    }

    public function getSubject(): object
    {
        return $this->subject;
    }
}
