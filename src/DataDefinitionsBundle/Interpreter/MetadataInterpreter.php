<?php

declare(strict_types=1);

/*
 * This source file is available under two different licenses:
 *  - GNU General Public License version 3 (GPLv3)
 *  - Data Definitions Commercial License (DDCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh) in combination with instride AG (https://instride.ch)
 * @license    GPLv3 and DDCL
 */

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Interpreter;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use OpenDxp\Model\DataObject\Data\ElementMetadata;
use OpenDxp\Model\DataObject\Data\ObjectMetadata;

class MetadataInterpreter implements InterpreterInterface
{
    public function interpret(InterpreterContextInterface $context): mixed
    {
        $class = '\\OpenDxp\\Model\\DataObject\\Data\\' . $context->getConfiguration()['class'];
        $fieldname = $context->getMapping()->getToColumn();

        $metadata = $context->getConfiguration()['metadata'];
        $metadata = json_decode($metadata, true, 512, \JSON_THROW_ON_ERROR);
        if (!is_array($metadata)) {
            $metadata = [];
        }

        /** @var ElementMetadata|ObjectMetadata $elementMetadata */
        $elementMetadata = new $class($fieldname, array_keys($metadata), $context->getValue());
        foreach ($metadata as $metadataKey => $metadataValue) {
            $setter = 'set' . ucfirst($metadataKey);
            $elementMetadata->$setter($metadataValue);
        }

        return $elementMetadata;
    }
}
