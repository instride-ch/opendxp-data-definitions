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

namespace Instride\Bundle\DataDefinitionsBundle\Command;

use Exception;
use Instride\Bundle\DataDefinitionsBundle\Importer\AsyncImporterInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Repository\DefinitionRepository;
use InvalidArgumentException;
use OpenDxp\Console\AbstractCommand;
use OpenDxp\Model\Exception\NotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Run a Data Definition Import Async.
 */
#[AsCommand(
    name: 'data-definitions:async-import',
    description: 'Run a Data Definition Import Async.'
)]
final class ImportAsyncCommand extends AbstractCommand
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly DefinitionRepository $repository,
        private readonly AsyncImporterInterface $importer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'definition',
                'd',
                InputOption::VALUE_REQUIRED,
                'Import Definition ID or Name',
            )
            ->addOption(
                'params',
                'p',
                InputOption::VALUE_REQUIRED,
                'JSON Encoded Params',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = json_decode($input->getOption('params'), true);
        $definitionId = $input->getOption('definition');

        if (!isset($params['userId'])) {
            $params['userId'] = 0;
        }

        try {
            if (filter_var($definitionId, \FILTER_VALIDATE_INT)) {
                $definition = $this->repository->find($definitionId);
            } else {
                $definition = $this->repository->findByName($definitionId);
            }
        } catch (NotFoundException) {
        }

        if (!$definition instanceof ImportDefinitionInterface) {
            throw new Exception(sprintf('Import Definition with ID/Name "%s" not found', $definitionId));
        }

        $this->importer->doImportAsync($definition, $params);

        return Command::SUCCESS;
    }
}
