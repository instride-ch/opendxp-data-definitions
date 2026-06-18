<?php
/**
 * Import Definitions.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright 2024 instride AG (https://instride.ch)
 * @license   https://github.com/instride-ch/DataDefinitions/blob/5.0/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Exception;
use Instride\Bundle\DataDefinitionsBundle\Installer;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinition;
use Instride\Bundle\DataDefinitionsBundle\Model\ExportDefinition;

final class IMSetupContext implements Context
{
    private static bool $setupDone = false;

    /**
     * @BeforeSuite
     */
    public static function setupImportDefinitions(): void
    {
        if (getenv('IM_SKIP_DB_SETUP')) {
            return;
        }

        if (static::$setupDone) {
            return;
        }

        $installer = \OpenDxp::getContainer()->get(Installer::class);
        $installer->install();

        static::$setupDone = true;
    }

    /**
     * @BeforeScenario
     * @throws Exception
     */
    public function purgeDefinitions(): void
    {
        $importDefinitions = new ImportDefinition\Listing();

        foreach ($importDefinitions->getObjects() as $definition) {
            $definition->delete();
        }

        $exportDefinitions = new ExportDefinition\Listing();

        foreach ($exportDefinitions->getObjects() as $definition) {
            $definition->delete();
        }
    }
}
