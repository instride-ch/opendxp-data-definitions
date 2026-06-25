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
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @copyright  Modification Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

use FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle;
use Instride\Bundle\DataDefinitionsBundle\DataDefinitionsBundle;
use OpenDxp\HttpKernel\BundleCollection\BundleCollection;
use OpenDxp\Kernel as OpenDxpKernel;

class BehatKernel extends OpenDxpKernel
{
    public function registerBundlesToCollection(BundleCollection $collection): void
    {
        $collection->addBundle(new DataDefinitionsBundle());
        $collection->addBundle(new FriendsOfBehatSymfonyExtensionBundle());
    }

    public function boot(): void
    {
        parent::boot();

        \OpenDxp::setKernel($this);
    }
}
