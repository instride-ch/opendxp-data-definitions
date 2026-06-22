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

namespace Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;

class RemoveEcommerceClassesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('EcommerceCoreBundle', $bundles)) {
            return;
        }

        $classesDir = OPENDXP_PROJECT_ROOT . '/var/classes/DataObject';
        if (!is_dir($classesDir)) {
            return;
        }

        $filesystem = new Filesystem();
        $iterator = new \RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($classesDir, RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if (!$file->isFile() || !str_ends_with($file->getFilename(), '.php')) {
                continue;
            }

            // Only remove generated class files that actually reference a now-missing
            // ecommerce class, so they cannot fatal on load. Matching the filename alone
            // would also delete unrelated project classes that merely contain "Ecommerce"
            // in their name.
            $contents = file_get_contents($file->getPathname());
            if ($contents !== false && str_contains($contents, 'OpenDxp\\Ecommerce\\')) {
                $filesystem->remove($file->getPathname());
            }
        }

        // Also remove empty directories
        $iterator = new \RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($classesDir, FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $dir) {
            if ($dir->isDir()) {
                $files = scandir($dir->getPathname());
                if ($files === false || count($files) === 2) { // Only . and ..
                    $filesystem->remove($dir->getPathname());
                }
            }
        }
    }
}
