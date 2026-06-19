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

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'data-definition:configuration:importer:convert-to-yaml',
    description: 'Convert import file definitions to YAML files'
)]
final class ConvertImportDefinitionsToYaml extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp('This command converts import file definitions file to YAML files')
            ->addArgument('file', InputArgument::OPTIONAL, 'Path to the PHP file', 'var/config/importdefinitions.php')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');
        $data = require $filePath;

        $fs = new Filesystem();
        if (!$fs->exists('var/config/import-definitions')) {
            $fs->mkdir('var/config/import-definitions');
        }

        foreach ($data as $entry) {
            $fileName = $entry['id'] . '.yaml';
            $yamlData = [
                'data_definitions' => [
                    'import_definitions' => [
                        $entry['id'] => $entry,
                    ],
                ],
            ];

            $yaml = Yaml::dump($yamlData, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);

            file_put_contents("var/config/import-definitions/{$fileName}", $yaml);
        }
        $output->writeln('YAML import definitions configurations are generated under: var/config/import-definitions');

        return Command::SUCCESS;
    }
}
