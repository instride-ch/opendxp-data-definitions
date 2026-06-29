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

use Exception;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\IdGenerator;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportDefinition;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportMapping;
use OpenDxp\Model;

/**
 * @var ImportDefinition $model
 */
class Dao extends Model\Dao\OpenDxpLocationAwareConfigDao
{
    use IdGenerator;

    private const string CONFIG_KEY = 'import_definitions';

    /**
     * Configure Configuration File
     */
    public function configure(): void
    {
        $config = \OpenDxp::getContainer()->getParameter('data_definitions.config_location');
        $definitions = \OpenDxp::getContainer()->getParameter('data_definitions.import_definitions');

        $storageConfig = $config[self::CONFIG_KEY];

        parent::configure([
            'containerConfig' => $definitions,
            'settingsStoreScope' => 'data_definitions.' . self::CONFIG_KEY,
            'storageConfig' => $storageConfig,
        ]);
    }

    protected function assignVariablesToModel($data): void
    {
        parent::assignVariablesToModel($data);

        foreach ($data as $key => $value) {
            if ($key === 'mapping') {
                $maps = [];

                foreach ($this->model->getMapping() as $map) {
                    if (\is_array($map)) {
                        $mapObj = new ImportMapping();
                        $mapObj->setValues($map);

                        $maps[] = $mapObj;
                    }
                }

                $this->model->setMapping($maps);
            }
        }
    }

    /**
     * @throws Model\Exception\NotFoundException
     */
    public function getById(string $id): void
    {
        $data = $this->getDataByName($id);

        if ($data) {
            $data['id'] = $id;
            $this->assignVariablesToModel($data);
        } else {
            throw new Model\Exception\NotFoundException(sprintf(
                'Import Definition with ID "%s" does not exist.',
                $id,
            ));
        }
    }

    public function getByName(string $name): void
    {
        foreach ($this->loadIdList() as $id) {
            $definition = ImportDefinition::getById((int) $id);

            if ($definition->getName() === $name) {
                $this->getById((string) $id);

                return;
            }
        }

        throw new Model\Exception\NotFoundException(sprintf(
            'Import Definition with Name "%s" does not exist.',
            $name,
        ));
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        $ts = time();

        if (!$this->model->getId()) {
            $this->model->setId($this->getSuggestedId(new Listing()));
        }

        if (!$this->model->getCreationDate()) {
            $this->model->setCreationDate($ts);
        }
        $this->model->setModificationDate($ts);

        $dataRaw = is_object($this->model) ? get_object_vars($this->model) : (array) $this->model;
        $data = [];
        $allowedProperties = [
            'id',
            'name',
            'provider',
            'class',
            'configuration',
            'creationDate',
            'modificationDate',
            'mapping',
            'objectPath',
            'cleaner',
            'key',
            'renameExistingObjects',
            'relocateExistingObjects',
            'filter',
            'runner',
            'createVersion',
            'stopOnException',
            'omitMandatoryCheck',
            'failureNotificationDocument',
            'successNotificationDocument',
            'skipExistingObjects',
            'skipNewObjects',
            'forceLoadObject',
            'loader',
            'fetcher',
            'persister',
        ];

        foreach ($dataRaw as $key => $value) {
            if (in_array($key, $allowedProperties, true)) {
                if ($key === 'providerConfiguration') {
                    if ($value) {
                        $data[$key] = get_object_vars($value);
                    }
                } elseif ($key === 'mapping') {
                    if ($value) {
                        $data[$key] = [];

                        if (\is_array($value)) {
                            foreach ($value as $map) {
                                $data[$key][] = is_object($map) ? get_object_vars($map) : (array) $map;
                            }
                        }
                    }
                } else {
                    $data[$key] = $value;
                }
            }
        }

        $this->saveData((string) $this->model->getId(), $data);
    }

    protected function prepareDataStructureForYaml(string $id, mixed $data): array
    {
        return [
            'opendxp_data_definitions' => [
                'import_definitions' => [
                    $id => $data,
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function delete(): void
    {
        $this->deleteData((string) $this->model->getId());
    }
}
