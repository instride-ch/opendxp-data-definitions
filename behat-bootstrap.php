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

use OpenDxp\Bootstrap;

if (!defined('OPENDXP_PROJECT_ROOT')) {
    define(
        'OPENDXP_PROJECT_ROOT',
        getenv('OPENDXP_PROJECT_ROOT')
            ?: getenv('REDIRECT_OPENDXP_PROJECT_ROOT')
            ?: realpath(getcwd())
    );
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ .'/.github/ci/files/src/BehatKernel.php';

if (file_exists(OPENDXP_PROJECT_ROOT.'/opendxp/config/bootstrap.php')) {
    require_once OPENDXP_PROJECT_ROOT.'/opendxp/config/bootstrap.php';
} else {
    Bootstrap::setProjectRoot();
    Bootstrap::bootstrap();
}
