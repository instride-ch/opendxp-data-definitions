<?php

declare(strict_types=1);

/*
 * This source file is available under two different licenses:
 *  - GNU General Public License version 3 (GPLv3)
 *  - Data Definitions Commercial License (DDCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh) in combination with instride AG (https://instride.ch)
 * @license    GPLv3 and DDCL
 */

namespace Instride\Bundle\DataDefinitionsBundle\Interpreter\CoreShop;

use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Interpreter\InterpreterInterface;

if (interface_exists('OpenDxp\Ecommerce\Component\Core\Repository\CurrencyRepositoryInterface')) {
    final class MoneyInterpreter implements InterpreterInterface
    {
        private $currencyRepository;

        public function __construct(
            \OpenDxp\Ecommerce\Component\Core\Repository\CurrencyRepositoryInterface $currencyRepository,
        ) {
            $this->currencyRepository = $currencyRepository;
        }

        public function interpret(InterpreterContextInterface $context): mixed
        {
            $value = $this->getValue((string) $context->getValue(), $context);
            $currency = $this->resolveCurrency((string) $value, $context);

            if (null === $currency) {
                return null;
            }

            return new \OpenDxp\Ecommerce\Component\Core\Model\Money($value, $currency);
        }

        private function getValue(string $value, InterpreterContextInterface $context): int
        {
            $inputIsFloat = $context->getConfiguration()['isFloat'];

            $value = preg_replace('/[^0-9,.]+/', '', $value);

            if (\is_string($value)) {
                $value = str_replace(',', '.', $value);
                $value = (float) $value;
            }

            if ($inputIsFloat) {
                $value = (int) round(round($value, 2) * 100, 0);
            }

            return (int) $value;
        }

        private function resolveCurrency(string $value, InterpreterContextInterface $context): ?\OpenDxp\Ecommerce\Component\Core\Model\CurrencyInterface
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
}
