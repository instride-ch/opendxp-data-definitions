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
 * @license   https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Command;

use Exception;
use Instride\Bundle\DataDefinitionsBundle\Event\ExportDefinitionEvent;
use Instride\Bundle\DataDefinitionsBundle\Exporter\ExporterInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ExportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Repository\DefinitionRepository;
use OpenDxp\Console\AbstractCommand;
use OpenDxp\Model\Exception\NotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsCommand(
    name: 'data-definitions:export',
    description: 'Run a Data Definition Export.'
)]
final class ExportCommand extends AbstractCommand
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly DefinitionRepository $repository,
        private readonly ExporterInterface $exporter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> runs a Data Definition Export.
EOT
            )
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

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $eventDispatcher = $this->eventDispatcher;

        $params = json_decode($input->getOption('params'), true);
        $definitionId = $input->getOption('definition');

        $definition = null;

        try {
            if (filter_var($definitionId, \FILTER_VALIDATE_INT)) {
                $definition = $this->repository->find($definitionId);
            } else {
                $definition = $this->repository->findByName($definitionId);
            }
        } catch (NotFoundException) {
        }

        if (!$definition instanceof ExportDefinitionInterface) {
            throw new Exception(sprintf('Export Definition with ID/Name "%s" not found', $definitionId));
        }

        $progress = null;

        if (!is_array($params)) {
            $params = [];
        }

        $imStatus = function (ExportDefinitionEvent $e) use (&$progress) {
            if ($progress instanceof ProgressBar) {
                $progress->setMessage($e->getSubject());
                $progress->display();
            }
        };

        $imTotal = function (ExportDefinitionEvent $e) use ($output, &$progress) {
            $total = $e->getSubject();
            if ($total > 0) {
                $progress = new ProgressBar($output, $total);
                $progress->setFormat(
                    ' %current%/%max% [%bar%] %percent:3s%% (%elapsed:6s%/%estimated:-6s%) %memory:6s%: %message%',
                );
                $progress->start();
            }
        };

        $imProgress = function (ExportDefinitionEvent $e) use (&$progress) {
            if ($progress instanceof ProgressBar) {
                $progress->advance();
            }
        };

        $imFinished = function (ExportDefinitionEvent $e) use ($output, &$progress) {
            if ($progress instanceof ProgressBar) {
                $output->writeln('');
            } else {
                $output->writeln('<info>No items to export</info>');
            }

            $output->writeln('Export finished!');
            $output->writeln('');
        };

        $eventDispatcher->addListener('data_definitions.export.status', $imStatus);
        $eventDispatcher->addListener('data_definitions.export.total', $imTotal);
        $eventDispatcher->addListener('data_definitions.export.progress', $imProgress);
        $eventDispatcher->addListener('data_definitions.export.finished', $imFinished);

        $this->exporter->doExport($definition, $params);

        $eventDispatcher->removeListener('data_definitions.export.status', $imStatus);
        $eventDispatcher->removeListener('data_definitions.export.total', $imTotal);
        $eventDispatcher->removeListener('data_definitions.export.progress', $imProgress);
        $eventDispatcher->removeListener('data_definitions.export.finished', $imFinished);

        return Command::SUCCESS;
    }
}
