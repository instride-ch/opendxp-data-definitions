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
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Context;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\MappingInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Provider\ImportDataSetInterface;
use OpenDxp\Model\DataObject\Concrete;

interface InterpreterContextInterface extends ContextInterface
{
    public function getDataRow(): array;

    public function getDataSet(): ?ImportDataSetInterface;

    public function getObject(): Concrete;

    public function getValue(): mixed;

    public function getMapping(): MappingInterface;
}
