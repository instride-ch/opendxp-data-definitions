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

namespace Instride\Bundle\DataDefinitionsBundle\Form\DataMapper;

use Instride\Bundle\DataDefinitionsBundle\Model\MappingInterface;
use Symfony\Component\Form\DataMapperInterface;

// TODO - Miguel - ???
final class DefinitionMappingDataMapper implements DataMapperInterface
{
    public function mapDataToForms($data, $forms): void
    {
    }

    public function mapFormsToData($forms, &$data): void
    {
        $actualData = [];

        foreach ($forms as $key => $form) {
            $formData = $form->getData();

            if (!$formData instanceof MappingInterface) {
                continue;
            }

            $actualData[] = $formData;
        }

        $data = $actualData;
    }
}
