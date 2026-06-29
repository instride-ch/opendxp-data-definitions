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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler;

final class FilterRegistryCompilerPass extends RegisterSimpleRegistryTypePass
{
    public const string FILTER_TAG = 'data_definitions.filter';

    public function __construct(
        ) {
        parent::__construct(
            'data_definitions.registry.filter',
            'data_definitions.filters',
            self::FILTER_TAG,
        );
    }
}
