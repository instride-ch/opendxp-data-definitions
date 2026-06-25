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

namespace Instride\Bundle\DataDefinitionsBundle\Behat\Context\Configuration;

use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class ServiceConfigurationContext implements Context
{
    private const SOURCE_DIR = __DIR__ . '/../../../../../src/DataDefinitionsBundle';
    private const CONFIG_DIR = self::SOURCE_DIR . '/Resources/config';
    private const NAMESPACE_PREFIX = 'Instride\\Bundle\\DataDefinitionsBundle\\';

    /**
     * @Then every form-type referenced in the service configuration should exist
     */
    public function everyFormTypeShouldExist(): void
    {
        $this->assertReferencedFilesExist($this->collectReferences('/form-type:\s*([A-Za-z0-9_\\\\]+)/'));
    }

    /**
     * @Then every service class referenced in the service configuration should exist
     */
    public function everyServiceClassShouldExist(): void
    {
        $this->assertReferencedFilesExist($this->collectReferences('/^\s*class:\s*([A-Za-z0-9_\\\\]+)\s*$/m'));
    }

    /**
     * @return array<string, string> class => file it was referenced in
     */
    private function collectReferences(string $pattern): array
    {
        $references = [];

        foreach (glob(self::CONFIG_DIR . '/{,services/,services/contexts/}*.yml', \GLOB_BRACE) ?: [] as $file) {
            preg_match_all($pattern, (string) file_get_contents($file), $matches);

            foreach ($matches[1] as $class) {
                $class = ltrim($class, '\\');

                // Only the bundle's own classes are checked: a rename or deletion
                // of those is what silently breaks a conditionally loaded config.
                // class_exists() is unusable here because some ecommerce form-types
                // are conditionally declared (guarded by class_exists on an ecommerce
                // dependency), so the file is the source of truth, not the symbol.
                if (str_starts_with($class, self::NAMESPACE_PREFIX)) {
                    $references[$class] = basename($file);
                }
            }
        }

        return $references;
    }

    /**
     * @param array<string, string> $references
     */
    private function assertReferencedFilesExist(array $references): void
    {
        Assert::notEmpty($references, 'No bundle class references found in the service configuration.');

        foreach ($references as $class => $file) {
            $relative = substr($class, \strlen(self::NAMESPACE_PREFIX));
            $path = self::SOURCE_DIR . '/' . str_replace('\\', '/', $relative) . '.php';

            Assert::fileExists(
                $path,
                sprintf('Class "%s" referenced in "%s" has no source file at %s.', $class, $file, $path),
            );
        }
    }
}
