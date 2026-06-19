<?php

declare(strict_types=1);

/**
 * OpenDXP
 *
 * This source file is licensed under the GNU General Public License version 3 (GPLv3).
 *
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.com)
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Behat\Support\DataObject;

use const JSON_THROW_ON_ERROR;
use Instride\Bundle\DataDefinitionsBundle\Behat\Support\Exception\ClassDefinitionNotFoundException;
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
