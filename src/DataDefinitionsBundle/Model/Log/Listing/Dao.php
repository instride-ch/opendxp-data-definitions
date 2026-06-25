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

namespace Instride\Bundle\DataDefinitionsBundle\Model\Log\Listing;

use Doctrine\DBAL\Query\QueryBuilder as DoctrineQueryBuilder;
use Exception;
use Instride\Bundle\DataDefinitionsBundle\Model\Log;
use OpenDxp\Model\Listing;
use OpenDxp\Model\Listing\Dao\QueryBuilderHelperTrait;

class Dao extends Listing\Dao\AbstractDao
{
    use QueryBuilderHelperTrait;

    protected string $tableName = 'data_definitions_import_log';

    /**
     * @throws Exception
     */
    public function load(): array
    {
        $list = $this->loadIdList();

        $objects = [];
        foreach ($list as $o_id) {
            if ($object = Log::getById($o_id)) {
                $objects[] = $object;
            }
        }

        $this->model->setObjects($objects);

        return $objects;
    }

    public function getQueryBuilder(...$columns): DoctrineQueryBuilder
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select(...$columns)->from($this->getTableName());

        $this->applyListingParametersToQueryBuilder($queryBuilder);

        return $queryBuilder;
    }

    /**
     * Loads a list for the specified parameters, returns an array of ids.
     *
     *
     * @throws Exception
     */
    public function loadIdList(): array
    {
        $queryBuilder = $this->getQueryBuilder(['id']);
        $assetIds = $this->db->fetchFirstColumn(
            (string) $queryBuilder,
            $this->model->getConditionVariables(),
            $this->model->getConditionVariableTypes(),
        );

        return array_map('intval', $assetIds);
    }

    /**
     * @throws Exception
     */
    public function getCount(): int
    {
        return (int) $this->db->fetchOne(
            'SELECT COUNT(*) as amount FROM ' . $this->getTableName() . $this->getCondition() . $this->getOffsetLimit(),
            [$this->model->getConditionVariables()],
        );
    }

    /**
     * @throws Exception
     */
    public function getTotalCount(): int
    {
        return (int) $this->db->fetchOne(
            'SELECT COUNT(*) as amount FROM ' . $this->getTableName() . $this->getCondition(),
            [$this->model->getConditionVariables()],
        );
    }

    protected function getTableName(): string
    {
        return $this->tableName;
    }
}
