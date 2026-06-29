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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportDefinition;

use OpenDxp\Model\Listing\AbstractListing;
use OpenDxp\Model\Listing\CallableFilterListingInterface;
use OpenDxp\Model\Listing\Traits\FilterListingTrait;

/**
 * @method load()
 * @method getAllIds()
 */
class Listing extends AbstractListing implements CallableFilterListingInterface
{
    use FilterListingTrait;

    public ?array $definitions = null;

    public function getObjects(): ?array
    {
        if (null === $this->definitions) {
            $this->load();
        }

        return $this->definitions;
    }

    public function setObjects(array $definitions): void
    {
        $this->definitions = $definitions;
    }
}
