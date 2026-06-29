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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Support\DataObject;

use const JSON_THROW_ON_ERROR;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Support\Exception\ClassDefinitionNotFoundException;
use OpenDxp\Model\DataObject;

class ClassUpdate extends AbstractDefinitionUpdate
{
    private readonly DataObject\ClassDefinition $classDefinition;

    public function __construct(
        string $className,
    ) {
        parent::__construct();

        $classDefinition = DataObject\ClassDefinition::getByName($className);

        if (null === $classDefinition) {
            throw new ClassDefinitionNotFoundException(sprintf('ClassDefinition %s not found', $className));
        }

        $this->classDefinition = $classDefinition;
        $this->fieldDefinitions = $this->classDefinition->getFieldDefinitions();
        $this->jsonDefinition = json_decode(
            DataObject\ClassDefinition\Service::generateClassDefinitionJson($this->classDefinition),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );
        $this->originalJsonDefinition = $this->jsonDefinition;
    }

    public function save(): bool
    {
        return DataObject\ClassDefinition\Service::importClassDefinitionFromJson(
            $this->classDefinition,
            json_encode($this->jsonDefinition, JSON_THROW_ON_ERROR),
            true,
        );
    }
}
