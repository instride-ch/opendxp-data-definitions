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
 * @copyright  Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Command;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Repository\DefinitionRepository;
use OpenDxp\Console\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * List all Import Definitions.
 *
 * The <info>%command.name%</info> lists all Data Definitions for Imports.
 */
#[AsCommand(
    name: 'data-definitions:list:imports',
    description: 'List all Import Definitions.',
)]
abstract class AbstractListDefinitionCommand extends AbstractCommand
{
    public function __construct(
        private readonly DefinitionRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $definitions = $this->repository->getAll();
        $data = [];

        /** @var ImportDefinitionInterface $definition */
        foreach ($definitions as $definition) {
            $data[] = [
                $definition->getId(),
                $definition->getName(),
                $definition->getProvider(),
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['ID', 'Name', 'Provider'])
            ->setRows($data)
        ;
        $table->render();

        return Command::SUCCESS;
    }
}
