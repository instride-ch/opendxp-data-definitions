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

namespace Instride\Bundle\DataDefinitionsBundle\Model\Log;

use function count;
use Exception;
use function in_array;
use InvalidArgumentException;
use function is_bool;
use function is_callable;
use OpenDxp\Model\Dao\AbstractDao;

class Dao extends AbstractDao
{
    protected string $tableName = 'data_definitions_import_log';

    /**
     * @param null $id
     *
     * @throws Exception
     */
    public function getById($id = null): void
    {
        if ($id !== null) {
            $this->model->setId($id);
        }

        $data = $this->db->fetchAssociative('SELECT * FROM ' . $this->tableName . ' WHERE id = ?', [$this->model->getId()]);

        if (!$data['id']) {
            throw new InvalidArgumentException(sprintf('Object with the ID %s does not exist', $this->model->getId()));
        }

        $this->assignVariablesToModel($data);
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        $vars = $this->model->getObjectVars();

        $buffer = [];

        $validColumns = $this->getValidTableColumns($this->tableName);

        if (count($vars)) {
            foreach ($vars as $k => $v) {
                if (!in_array($k, $validColumns, true)) {
                    continue;
                }

                $getter = sprintf('get%s', ucfirst($k));

                if (!is_callable([$this->model, $getter])) {
                    continue;
                }

                $value = $this->model->$getter();

                if (is_bool($value)) {
                    $value = (int) $value;
                }

                $buffer[$k] = $value;
            }
        }

        if ($this->model->getId() !== null) {
            $this->db->update($this->tableName, $buffer, ['id' => $this->model->getId()]);

            return;
        }

        $this->db->insert($this->tableName, $buffer);
        $this->model->setId((int) $this->db->lastInsertId());
    }

    /**
     * Delete vote
     *
     * @throws Exception
     */
    public function delete(): void
    {
        $this->db->delete($this->tableName, ['id' => $this->model->getId()]);
    }
}
