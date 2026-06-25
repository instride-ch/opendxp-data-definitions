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

use Instride\Bundle\DataDefinitionsBundle\Behat\Support\Exception\ClassDefinitionNotFoundException;
use OpenDxp\Model\DataObject;

class FieldCollectionDefinitionUpdate extends AbstractDefinitionUpdate
{
    private readonly DataObject\Fieldcollection\Definition $fieldCollectionDefinition;

    public function __construct(
        string $fieldCollectionKey,
    ) {
        parent::__construct();

        $fieldCollectionDefinition = DataObject\Fieldcollection\Definition::getByKey($fieldCollectionKey);

        if (null === $fieldCollectionDefinition) {
            throw new ClassDefinitionNotFoundException(sprintf('Fieldcollection Definition %s not found', $fieldCollectionKey));
        }

        $this->fieldCollectionDefinition = $fieldCollectionDefinition;
        $this->fieldDefinitions = $this->fieldCollectionDefinition->getFieldDefinitions();
        $this->jsonDefinition = json_decode(DataObject\ClassDefinition\Service::generateFieldCollectionJson($this->fieldCollectionDefinition), true);
        $this->originalJsonDefinition = $this->jsonDefinition;
    }

    public function save(): bool
    {
        return DataObject\ClassDefinition\Service::importFieldCollectionFromJson($this->fieldCollectionDefinition, json_encode($this->jsonDefinition), true);
    }
}
