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

namespace Instride\Bundle\DataDefinitionsBundle\Setter\Ecommerce;

use Instride\Bundle\DataDefinitionsBundle\Context\GetterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Context\SetterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Getter\GetterInterface;
use Instride\Bundle\DataDefinitionsBundle\Setter\SetterInterface;
use InvalidArgumentException;
use function is_array;
use OpenDxp\Ecommerce\Component\Core\Model\StoreInterface;
use OpenDxp\Ecommerce\Component\Store\Repository\StoreRepositoryInterface;

class StoreValuesSetter implements SetterInterface, GetterInterface
{
    public function __construct(
        private StoreRepositoryInterface $storeRepository,
    ) {
    }

    public function set(SetterContextInterface $context)
    {
        $config = $context->getMapping()->getSetterConfig();

        if (!array_key_exists('stores', $config) || !is_array($config['stores'])) {
            return;
        }

        foreach ($config['stores'] as $store) {
            $store = $this->storeRepository->find($store);

            if (!$store instanceof StoreInterface) {
                throw new InvalidArgumentException(sprintf('Store with ID %s not found', $config['store']));
            }

            $setter = sprintf('set%sOfType', ucfirst($context->getMapping()->getToColumn()));

            if (!method_exists($context->getObject(), $setter)) {
                throw new InvalidArgumentException(sprintf('Expected a %s function but can not find it', $setter));
            }

            $context->getObject()->$setter($config['type'], $context->getValue(), $store);
        }
    }

    public function get(GetterContextInterface $context)
    {
        $config = $context->getMapping()->getGetterConfig();

        if (!array_key_exists('stores', $config) || !is_array($config['stores'])) {
            return [];
        }

        $values = [];

        foreach ($config['stores'] as $store) {
            $store = $this->storeRepository->find($store);

            if (!$store instanceof StoreInterface) {
                throw new InvalidArgumentException(sprintf('Store with ID %s not found', $config['store']));
            }

            $getter = sprintf('get%sOfType', ucfirst($context->getMapping()->getFromColumn()));

            if (!method_exists($context->getObject(), $getter)) {
                throw new InvalidArgumentException(sprintf('Expected a %s function but can not find it', $getter));
            }

            $values[$store->getId()] = $context->getObject()->$getter($config['type'], $store);
        }

        return $values;
    }
}
