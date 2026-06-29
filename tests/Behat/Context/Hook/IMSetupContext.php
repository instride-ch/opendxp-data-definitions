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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Exception;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Installer;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportDefinition;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ExportDefinition;

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
