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

use Instride\Bundle\DataDefinitionsBundle\Repository\DefinitionRepository;
use InvalidArgumentException;
use OpenDxp\Console\AbstractCommand;
use OpenDxp\Ecommerce\Bundle\ResourceBundle\Controller\ResourceFormFactory;
use OpenDxp\Ecommerce\Bundle\ResourceBundle\OpenDxp\ObjectManager;
use OpenDxp\Ecommerce\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractImportDefinitionCommand extends AbstractCommand
{
    public function __construct(
        protected readonly MetadataInterface $metadata,
        protected readonly DefinitionRepository $repository,
        protected readonly ObjectManager $manager,
        protected readonly ResourceFormFactory $resourceFormFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $type = $this->getType();

        $this
            ->setDescription(sprintf('Create a %s Definition.', $type))
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                sprintf('Path to %s Definition JSON export file', $type),
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $this->getPath();

        $jsonContent = file_get_contents($path);
        $data = json_decode($jsonContent, true);

        try {
            $definition = $this->repository->findByName($data['name']);
        } catch (InvalidArgumentException $e) {
            $class = $this->repository->getModelClass();
            $definition = new $class();
        }

        $form = $this->resourceFormFactory->create($this->metadata, $definition);
        $handledForm = $form->submit($data);

        if (!$handledForm->isValid()) {
            foreach ($handledForm->getErrors() as $error) {
                $this->writeError($error->getMessage());
            }

            return 1;
        }

        $definition = $form->getData();
        $this->manager->persist($definition);
        $this->manager->flush();

        return Command::SUCCESS;
    }

    /**
     * Validate and return path to JSON file
     *
     * @throws InvalidArgumentException
     */
    protected function getPath(): string
    {
        $path = $this->input->getArgument('path');
        if (!file_exists($path) || !is_readable($path)) {
            throw new InvalidArgumentException('File does not exist');
        }

        return $path;
    }

    /**
     * Get type
     */
    abstract protected function getType(): string;
}
