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

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Support\Exception\ClassDefinitionNotFoundException;
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
