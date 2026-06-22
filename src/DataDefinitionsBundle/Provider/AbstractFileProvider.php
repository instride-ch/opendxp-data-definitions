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
 * @license   https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Provider;

use Exception;
use Instride\Bundle\DataDefinitionsBundle\Service\StorageLocator;
use League\Flysystem\FilesystemException;
use OpenDxp\File;
use OpenDxp\Helper\LongRunningHelper;
use OpenDxp\Model\Asset;

abstract class AbstractFileProvider
{
    public function __construct(
        protected StorageLocator $storageLocator,
        protected LongRunningHelper $longRunningHelper,
    ) {
    }

    /**
     * @throws FilesystemException
     * @throws Exception
     */
    protected function getFile(array $params): string
    {
        if (isset($params['asset'])) {
            $asset = Asset::getByPath($params['asset']);

            if (!$asset) {
                throw new \RuntimeException(sprintf('Asset "%s" not found', $params['asset']));
            }

            return $this->createTemporaryFileFromStream($asset->getStream());
        }

        if (isset($params['storage'], $params['file'])) {
            $storage = $this->storageLocator->getStorage($params['storage']);

            if (!$storage->fileExists($params['file'])) {
                throw new \RuntimeException(sprintf('File "%s" in Storage "%s" not found', $params['file'], $params['storage']));
            }

            return $this->createTemporaryFileFromStream($storage->readStream($params['file']));
        }

        if (isset($params['file'])) {
            return $params['file'];
        }

        throw new \RuntimeException('No file or asset given');
    }

    /**
     * @throws Exception
     */
    protected function createTemporaryFileFromStream($stream): string
    {
        if (is_string($stream)) {
            $src = fopen($stream, 'rb');
            $fileExtension = pathinfo($stream, \PATHINFO_EXTENSION);
        } else {
            $src = $stream;
            $streamMeta = stream_get_meta_data($src);
            $fileExtension = pathinfo($streamMeta['uri'], \PATHINFO_EXTENSION);
        }

        $tmpFilePath = File::getLocalTempFilePath($fileExtension);

        $dest = fopen($tmpFilePath, 'wb', false, File::getContext());
        if (!$dest) {
            throw new Exception(sprintf('Unable to create temporary file in %s', $tmpFilePath));
        }

        stream_copy_to_stream($src, $dest);
        fclose($dest);

        $this->longRunningHelper->addTmpFilePath($tmpFilePath);
        register_shutdown_function(static function () use ($tmpFilePath) {
            @unlink($tmpFilePath);
        });

        return $tmpFilePath;
    }
}
