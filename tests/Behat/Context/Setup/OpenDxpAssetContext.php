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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Exception;
use OpenDxp\Model\Asset;
use Symfony\Component\HttpKernel\KernelInterface;

final readonly class OpenDxpAssetContext implements Context
{

    public function __construct(
        private KernelInterface $kernel
    ) {
    }

    /**
     * @Given /^there is a asset with bundle file "([^"]+)"$/
     * @Given /^there is a asset with bundle file "([^"]+)" at path "([^"]+)"$/
     * @throws Exception
     */
    public function thereIsAAssetWithBundleFile(string $bundleFile, ?string $parentPath = null): void
    {
        $path = $this->kernel->locateResource($bundleFile);
        $parentId = 1;

        if (null !== $parentPath) {
            $parentId = Asset\Service::createFolderByPath($parentPath)->getId();
        }

        Asset::create($parentId, [
            'filename' => basename($path),
            'sourcePath' => $path
        ]);
    }
}
