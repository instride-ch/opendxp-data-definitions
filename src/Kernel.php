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

namespace Instride\Bundle\DataDefinitionsBundle;

use OpenDxp\HttpKernel\BundleCollection\BundleCollection;
use OpenDxp\Kernel as OpenDxpKernel;

class Kernel extends OpenDxpKernel
{
    public function registerBundlesToCollection(BundleCollection $collection): void
    {
        $collection->addBundle(new DataDefinitionsBundle());
        $collection->addBundle(new \FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle());
    }

    public function boot(): void
    {
        parent::boot();

        \OpenDxp::setKernel($this);
    }
}
