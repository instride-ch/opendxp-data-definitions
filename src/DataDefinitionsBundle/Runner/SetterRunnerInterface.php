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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Runner;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Model\DataDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Model\MappingInterface;
use OpenDxp\Model\DataObject\Concrete;

interface SetterRunnerInterface extends RunnerInterface
{
    public function shouldSetField(
        Concrete $object,
        MappingInterface $map,
        $value,
        array $data,
        DataDefinitionInterface $definition,
        array $params,
    );
}
