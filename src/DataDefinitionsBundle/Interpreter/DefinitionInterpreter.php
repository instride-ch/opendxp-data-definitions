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
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @copyright  Modification Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter;

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Importer\ImporterInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Repository\DefinitionRepository;
use OpenDxp\Model\DataObject;

class DefinitionInterpreter implements InterpreterInterface
{
    public function __construct(
        private readonly DefinitionRepository $definitionRepository,
        private readonly ImporterInterface $importer,
    ) {
    }

    public function interpret(InterpreterContextInterface $context): mixed
    {
        $subDefinition = $this->definitionRepository->find($context->getConfiguration()['definition']);

        if (!$subDefinition instanceof ImportDefinitionInterface) {
            return null;
        }

        $imported = $this->importer->doImport($subDefinition, ['data' => [$context->getDataRow()], 'child' => true]);

        if (count($imported) === 1) {
            return DataObject::getById($imported[0]);
        }

        return array_map(static function ($id) {
            return DataObject::getById($id);
        }, $imported);
    }
}
