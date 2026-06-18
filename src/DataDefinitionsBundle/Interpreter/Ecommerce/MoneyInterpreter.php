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

use OpenDxp\Ecommerce\Component\Core\Repository\CurrencyRepositoryInterface;
use OpenDxp\Ecommerce\Component\Currency\Model\CurrencyInterface;
use OpenDxp\Ecommerce\Component\Currency\Model\Money;
use Instride\Bundle\DataDefinitionsBundle\Context\InterpreterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Interpreter\InterpreterInterface;


// TODO Miguel - Soll hier die Currency indexiert werden?
// müsste auch definiert werden, für welchen Store. DefaultStore als möglichkeit ohne Parameter möglich
final class MoneyInterpreter implements InterpreterInterface
{
    private CurrencyRepositoryInterface $currencyRepository;

    public function __construct(
        CurrencyRepositoryInterface $currencyRepository,
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

        return new Money($value, $currency);
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
