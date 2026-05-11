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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle;

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
