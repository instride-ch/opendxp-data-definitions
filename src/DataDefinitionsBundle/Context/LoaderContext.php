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

namespace Instride\Bundle\DataDefinitionsBundle\Context;

use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Provider\ImportDataSetInterface;

class LoaderContext extends Context implements LoaderContextInterface
{
    public function __construct(
        ImportDefinitionInterface $definition,
        array $params,
        array $configuration,
        protected array $dataRow,
        protected ImportDataSetInterface $dataSet,
        protected string $class,
    ) {
        parent::__construct($definition, $params, $configuration);
    }

    public function getDefinition(): ImportDefinitionInterface
    {
        /**
         * @var ImportDefinitionInterface $definition
         */
        $definition = $this->definition;

        return $definition;
    }

    public function getDataRow(): array
    {
        return $this->dataRow;
    }

    public function getDataSet(): ImportDataSetInterface
    {
        return $this->dataSet;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
