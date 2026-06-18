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

namespace Instride\Bundle\DataDefinitionsBundle\Form\Type\Interpreter\Ecommerce;

use OpenDxp\Ecommerce\Bundle\StoreBundle\Form\Type\StoreChoiceType;
use OpenDxp\Ecommerce\Component\Store\Model\StoreInterface;
use Doctrine\Common\Collections\ArrayCollection;
use function is_array;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

final class StoresInterpreterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stores', StoreChoiceType::class, [
                'multiple' => true,
            ])
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($value) {
                        return $value;
                    },
                    function ($value) {
                        $resolvedValues = [];

                        if (!is_array($value) ||
                            !array_key_exists('stores', $value) ||
                            !$value['stores'] instanceof ArrayCollection) {
                            return [];
                        }

                        foreach ($value['stores'] as $val) {
                            if ($val instanceof StoreInterface) {
                                $resolvedValues[] = $val->getId();
                            }
                        }

                        $value['stores'] = $resolvedValues;

                        return $value;
                    },
                ),
            )
        ;
    }
}
