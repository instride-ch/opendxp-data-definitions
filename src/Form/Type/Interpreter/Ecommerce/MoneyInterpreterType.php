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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Form\Type\Interpreter\Ecommerce;

use OpenDxp\Ecommerce\Bundle\CurrencyBundle\Form\Type\CurrencyChoiceType;
use OpenDxp\Ecommerce\Component\Currency\Model\CurrencyInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

if (class_exists('OpenDxp\Ecommerce\Bundle\CurrencyBundle\Form\Type\CurrencyChoiceType')) {
    final class MoneyInterpreterType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('isFloat', CheckboxType::class)
                ->add('currency', CurrencyChoiceType::class)
                ->addModelTransformer(
                    new CallbackTransformer(
                        function ($value) {
                            return $value;
                        },
                        function ($value) {
                            if (isset($value['currency']) && $value['currency'] instanceof CurrencyInterface) {
                                $value['currency'] = $value['currency']->getId();
                            }

                            return $value;
                        },
                    ),
                )
            ;
        }
    }
}
