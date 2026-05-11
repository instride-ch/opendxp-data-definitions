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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Context;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Model\DataDefinitionInterface;

interface ContextInterface
{
    public function getDefinition(): DataDefinitionInterface;

    public function getParams(): array;

    public function getConfiguration(): array;

    public function withConfiguration(array $configuration): self;
}
