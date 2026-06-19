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
 * @copyright 2026 instride AG (https://instride.ch)
 * @license   https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Command;

use Instride\Bundle\DataDefinitionsBundle\Form\Type\ImportDefinitionType;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'data-definitions:definition:import:import',
    description: 'Create an Import Definition.'
)]
final class ImportImportDefinitionCommand extends AbstractImportDefinitionCommand
{
    protected function getType(): string
    {
        return 'Import';
    }

    protected function getFormType(): string
    {
        return ImportDefinitionType::class;
    }
}
