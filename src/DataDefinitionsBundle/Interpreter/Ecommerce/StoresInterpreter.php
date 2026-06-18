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

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter\Ecommerce;

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Interpreter\InterpreterInterface;
use OpenDxp\Ecommerce\Bundle\StoreBundle\Doctrine\ORM\StoreRepository;

final class StoresInterpreter implements InterpreterInterface
{
    public function interpret(InterpreterContextInterface $context): mixed
    {
        // TODO Miguel
//        Required Parameter - Site (oder SiteId) (Store wird einer Site zugewiesen)
//        mit @StoreRepository kann es dann die Stores für die Site abrufen
        $store = $context->getValue();

        return $context->getConfiguration()['stores'];
    }
}
