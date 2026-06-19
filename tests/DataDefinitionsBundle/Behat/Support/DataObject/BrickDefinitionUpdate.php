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

class BrickDefinitionUpdate extends AbstractDefinitionUpdate
{
    private readonly DataObject\Objectbrick\Definition $brickDefinition;

    public function __construct(
        string $brickKey,
    ) {
        parent::__construct();

        $brickDefinition = DataObject\Objectbrick\Definition::getByKey($brickKey);

        if (null === $brickDefinition) {
            throw new ClassDefinitionNotFoundException(sprintf('Brick Definition %s not found', $brickKey));
        }

        $this->brickDefinition = $brickDefinition;
        $this->fieldDefinitions = $this->brickDefinition->getFieldDefinitions();
        $this->jsonDefinition = json_decode(DataObject\ClassDefinition\Service::generateObjectBrickJson($this->brickDefinition), true);
        $this->originalJsonDefinition = $this->jsonDefinition;
    }

    public function save(): bool
    {
        return DataObject\ClassDefinition\Service::importObjectBrickFromJson($this->brickDefinition, json_encode($this->jsonDefinition), true);
    }
}
