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

namespace Instride\Bundle\DataDefinitionsBundle;

use Exception;
use OpenDxp;
use OpenDxp\Extension\Bundle\Installer\SettingsStoreAwareInstaller;
use Symfony\Component\Console\Input\ArrayInput;

class Installer extends SettingsStoreAwareInstaller
{
    /**
     * @throws Exception
     */
    public function install(): void
    {
        $kernel = OpenDxp::getKernel();
        $application = new OpenDxp\Console\Application($kernel);
        $application->setAutoExit(false);
        $options = ['command' => 'ecommerce:resources:install'];
        $options = array_merge($options, ['--no-interaction' => true, '--application-name data_definitions']);
        $application->run(new ArrayInput($options));

        parent::install();
    }
}
