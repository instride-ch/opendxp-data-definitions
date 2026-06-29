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

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\DataDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportMapping;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Provider\ImportDataSetInterface;
use OpenDxp\Model\DataObject\Concrete;

class SetterContext extends Context implements SetterContextInterface
{
    public function __construct(
        DataDefinitionInterface $definition,
        array $params,
        array $configuration,
        protected Concrete $object,
        protected ImportMapping $mapping,
        protected array $dataRow,
        protected ImportDataSetInterface $dataSet,
        protected mixed $value,
    ) {
        parent::__construct($definition, $params, $configuration);
    }

    public function getObject(): Concrete
    {
        return $this->object;
    }

    public function getMapping(): ImportMapping
    {
        return $this->mapping;
    }

    public function getDataRow(): array
    {
        return $this->dataRow;
    }

    public function getDataSet(): ImportDataSetInterface
    {
        return $this->dataSet;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
