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
 * @copyright  Copyright (c) instride AG (https://instride.ch)
 * @license   https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class DefinitionEvent extends Event implements DefinitionEventInterface
{
    public const string PRE_SAVE = 'data_definitions.definition.pre_save';
    public const string POST_SAVE = 'data_definitions.definition.post_save';

    private object $subject;

    public function __construct(object $subject)
    {
        $this->subject = $subject;
    }

    public function getDefinition(): object
    {
        return $this->subject;
    }

    public function getSubject(): object
    {
        return $this->subject;
    }
}
