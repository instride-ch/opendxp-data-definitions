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

class ContextFactory implements ContextFactoryInterface
{
    public function createFetcherContext(
        ExportDefinitionInterface $definition,
        array $params,
        array $configuration,
    ): FetcherContextInterface {
        return new FetcherContext($definition, $params, $configuration);
    }

    public function createLoaderContext(
        ImportDefinitionInterface $definition,
        array $params,
        array $dataRow,
        ImportDataSetInterface $dataSet,
        string $class,
    ): LoaderContextInterface {
        return new LoaderContext($definition, $params, [], $dataRow, $dataSet, $class);
    }

    public function createFilterContext(
        DataDefinitionInterface $definition,
        array $params,
        array $dataRow,
        ImportDataSetInterface $dataSet,
        Concrete $object,
    ): FilterContextInterface {
        return new FilterContext($definition, $params, [], $dataRow, $dataSet, $object);
    }

    public function createGetterContext(
        DataDefinitionInterface $definition,
        array $params,
        Concrete $object,
        ExportMapping $mapping,
    ): GetterContextInterface {
        return new GetterContext($definition, $params, [], $object, $mapping);
    }

    public function createSetterContext(
        DataDefinitionInterface $definition,
        array $params,
        Concrete $object,
        ImportMapping $mapping,
        array $dataRow,
        ImportDataSetInterface $dataSet,
        mixed $value,
    ): SetterContextInterface {
        return new SetterContext($definition, $params, [], $object, $mapping, $dataRow, $dataSet, $value);
    }

    public function createInterpreterContext(
        DataDefinitionInterface $definition,
        array $params,
        array $configuration,
        array $dataRow,
        ?ImportDataSetInterface $dataSet,
        Concrete $object,
        mixed $value,
        MappingInterface $mapping,
    ): InterpreterContextInterface {
        return new InterpreterContext($definition, $params, $configuration, $dataRow, $dataSet, $object, $value, $mapping);
    }

    public function createRunnerContext(
        DataDefinitionInterface $definition,
        array $params,
        ?array $dataRow,
        ?ImportDataSetInterface $dataSet,
        ?Concrete $object,
    ): RunnerContextInterface {
        return new RunnerContext($definition, $params, [], $dataRow, $dataSet, $object);
    }
}
