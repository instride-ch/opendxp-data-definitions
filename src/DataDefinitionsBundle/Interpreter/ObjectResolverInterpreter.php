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
 * @license   https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter;

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use OpenDxp\Model\DataObject\Listing;

class ObjectResolverInterpreter implements InterpreterInterface
{
    public function interpret(InterpreterContextInterface $context): mixed
    {
        if (!$context->getValue()) {
            return null;
        }

        $class = 'OpenDxp\Model\DataObject\\' . ucfirst($context->getConfiguration()['class']);
        $lookup = 'getBy' . ucfirst($context->getConfiguration()['field']);

        /**
         * @var Listing $listing
         */
        $listing = $class::$lookup($context->getValue());
        $listing->setUnpublished($context->getConfiguration()['match_unpublished']);

        if ($listing->count() === 1) {
            return $listing->current();
        }

        return null;
    }
}
