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
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ExportDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ExportMapping;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportMapping;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\MappingInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Provider\ImportDataSetInterface;
use OpenDxp\Model\DataObject\Concrete;

interface ContextFactoryInterface
{
    public function createFetcherContext(
        ExportDefinitionInterface $definition,
        array $params,
        array $configuration,
    ): FetcherContextInterface;

    public function createLoaderContext(
        ImportDefinitionInterface $definition,
        array $params,
        array $dataRow,
        ImportDataSetInterface $dataSet,
        string $class,
    ): LoaderContextInterface;

    public function createFilterContext(
        DataDefinitionInterface $definition,
        array $params,
        array $dataRow,
        ImportDataSetInterface $dataSet,
        Concrete $object,
    ): FilterContextInterface;

    public function createGetterContext(
        DataDefinitionInterface $definition,
        array $params,
        Concrete $object,
        ExportMapping $mapping,
    ): GetterContextInterface;

    public function createSetterContext(
        DataDefinitionInterface $definition,
        array $params,
        Concrete $object,
        ImportMapping $mapping,
        array $dataRow,
        ImportDataSetInterface $dataSet,
        mixed $value,
    ): SetterContextInterface;

    public function createInterpreterContext(
        DataDefinitionInterface $definition,
        array $params,
        array $configuration,
        array $dataRow,
        ?ImportDataSetInterface $dataSet,
        Concrete $object,
        mixed $value,
        MappingInterface $mapping,
    ): InterpreterContextInterface;

    public function createRunnerContext(
        DataDefinitionInterface $definition,
        array $params,
        ?array $dataRow,
        ?ImportDataSetInterface $dataSet,
        ?Concrete $object,
    ): RunnerContextInterface;
}
