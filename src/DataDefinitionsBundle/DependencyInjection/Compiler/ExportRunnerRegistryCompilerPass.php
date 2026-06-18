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

namespace Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler;

final class ExportRunnerRegistryCompilerPass extends RegisterSimpleRegistryTypePass
{
    public const string EXPORT_RUNNER_TAG = 'data_definitions.export_runner';

    public function __construct(
        ) {
        parent::__construct(
            'data_definitions.registry.export_runner',
            'data_definitions.export_runners',
            self::EXPORT_RUNNER_TAG,
        );
    }
}
