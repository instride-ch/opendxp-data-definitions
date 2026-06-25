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

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter\Ecommerce;

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Interpreter\InterpreterInterface;
use OpenDxp\Ecommerce\Component\Core\Repository\CurrencyRepositoryInterface;
use OpenDxp\Ecommerce\Component\Currency\Model\CurrencyInterface;
use OpenDxp\Ecommerce\Component\Currency\Model\Money;

final class MoneyInterpreter implements InterpreterInterface
{
    public function __construct(
        private CurrencyRepositoryInterface $currencyRepository,
    ) {
    }

    public function interpret(InterpreterContextInterface $context): mixed
    {
        $currency = $this->resolveCurrency((string) $context->getValue(), $context);
        if (null === $currency) {
            return null;
        }

        return new Money($context->getValue(), $currency);
    }

    private function resolveCurrency(string $value, InterpreterContextInterface $context): ?CurrencyInterface
    {
        $currency = null;

        if (preg_match('/^\pL+$/u', $value)) {
            $currencyCode = preg_replace('/[^a-zA-Z]+/', '', $value);

            $currency = $this->currencyRepository->getByCode($currencyCode);
        }

        if ($currency === null && isset($context->getConfiguration()['currency']) && null !== $context->getConfiguration()['currency']) {
            $currency = $this->currencyRepository->find($context->getConfiguration()['currency']);
        }

        return $currency;
    }
}
