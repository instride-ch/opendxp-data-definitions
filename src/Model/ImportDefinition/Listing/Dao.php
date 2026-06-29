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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportDefinition\Listing;

use function count;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportDefinition;

class Dao extends ImportDefinition\Dao
{
    public function load(): array
    {
        $definitions = [];
        foreach ($this->loadIdList() as $id) {
            $definitions[] = ImportDefinition::getById((int) $id);
        }

        if ($this->model->getOrder()) {
            $orderKey = is_array($this->model->getOrderKey()) ? $this->model->getOrderKey()[0] : 'name';
            $order = $this->model->getOrder();
            usort($definitions, function ($a, $b) use ($orderKey, $order) {
                $orderKeyGetter = 'get' . ucfirst($orderKey);
                if (method_exists($a, $orderKeyGetter) && method_exists($b, $orderKeyGetter)) {
                    if ($order === 'ASC') {
                        return $a->$orderKeyGetter() < $b->$orderKeyGetter() ? -1 : 1;
                    }
                    if ($order === 'DESC') {
                        return $a->$orderKeyGetter() > $b->$orderKeyGetter() ? -1 : 1;
                    }

                    return 0;
                }

                return 0;
            });
        }

        $this->model->setObjects($definitions);

        return $definitions;
    }

    public function getAllIds(): array
    {
        return $this->loadIdList();
    }

    public function getTotalCount(): int
    {
        return count($this->load());
    }
}
