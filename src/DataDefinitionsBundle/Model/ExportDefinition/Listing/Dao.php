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

namespace Instride\Bundle\DataDefinitionsBundle\Model\ExportDefinition\Listing;

use function count;
use Instride\Bundle\DataDefinitionsBundle\Model\ExportDefinition;

class Dao extends ExportDefinition\Dao
{
    public function load(): array
    {
        $definitions = [];
        foreach ($this->loadIdList() as $id) {
            $definitions[] = ExportDefinition::getById((int) $id);
        }

        // TODO Miguel - check commented code
//        if ($this->model->getFilter()) {
//            $definitions = array_filter($definitions, $this->model->getFilter());
//        }
//        if ($this->model->getOrder()) {
//            usort($definitions, $this->model->getOrder());
//        }
//        $this->model->setObjects($definitions);

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
