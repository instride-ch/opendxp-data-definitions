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

use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Provider\ImportDataSetInterface;

interface LoaderContextInterface extends ContextInterface
{
    public function getDefinition(): ImportDefinitionInterface;

    public function getDataRow(): array;

    public function getDataSet(): ImportDataSetInterface;

    public function getClass(): string;
}
