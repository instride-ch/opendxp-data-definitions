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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'data-definitions:configuration:exporter:convert-to-yaml',
    description: 'Convert export definitions file to YAML files',
)]
final class ConvertExportDefinitionsToYaml extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp('This command converts export definitions file to YAML')
            ->addArgument('file', InputArgument::OPTIONAL, 'Path to the PHP file', 'var/config/exportdefinitions.php')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');
        $data = require $filePath;

        $fs = new Filesystem();
        if (!$fs->exists('var/config/export_definitions')) {
            $fs->mkdir('var/config/export_definitions');
        }

        foreach ($data as $entry) {
            $fileName = $entry['name'] . '.yaml';

            $yamlData = [
                'opendxp_data_definitions' => [
                    'export_definitions' => [
                        $entry['id'] => $entry,
                    ],
                ],
            ];

            $yaml = Yaml::dump($yamlData, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
            file_put_contents("var/config/export_definitions/{$fileName}", $yaml);
        }
        $output->writeln('YAML export definitions are generated under: var/config/export_definitions');

        return Command::SUCCESS;
    }
}
