<?php
/**
 * Ecommerce.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) Ecommerce GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

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

require_once __DIR__ .'/src/BehatKernel.php';

if (file_exists(OPENDXP_PROJECT_ROOT.'/opendxp/config/bootstrap.php')) {
    require_once OPENDXP_PROJECT_ROOT.'/opendxp/config/bootstrap.php';
}
else {
    \OpenDxp\Bootstrap::setProjectRoot();
    \OpenDxp\Bootstrap::bootstrap();
}
