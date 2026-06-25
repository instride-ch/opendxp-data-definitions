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

namespace Instride\Bundle\DataDefinitionsBundle\Form\Type;

use Instride\Bundle\DataDefinitionsBundle\Form\Registry\FormTypeRegistryInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportMapping;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImportMappingType extends AbstractType
{
    public function __construct(
        private FormTypeRegistryInterface $setterTypeRegistry,
        private FormTypeRegistryInterface $interpreterTypeRegistry,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImportMapping::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fromColumn', TextType::class)
            ->add('toColumn', TextType::class)
            ->add('primaryIdentifier', CheckboxType::class)
            ->add('setter', TextType::class)
            ->add('interpreter', TextType::class)
        ;

        /** Setter Configurations */
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $type = $this->getSetterRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $type = $this->getSetterRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }

                $event->getForm()->get('setter')->setData($type);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();

                if (!isset($data['setter'])) {
                    return;
                }

                if (!$formType = $this->setterTypeRegistry->get($data['setter'], 'default')) {
                    $formType = NoConfigurationType::class;
                }

                $this->addSetterConfigurationFields($event->getForm(), $formType);
            })
        ;

        /** Interpreter Configurations */
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $type = $this->getInterpreterRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $type = $this->getInterpreterRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }

                $event->getForm()->get('interpreter')->setData($type);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();

                if (!isset($data['interpreter'])) {
                    return;
                }

                if (!$formType = $this->interpreterTypeRegistry->get($data['interpreter'], 'default')) {
                    $formType = NoConfigurationType::class;
                }

                $this->addInterpreterConfigurationFields($event->getForm(), $formType);
            })
        ;
    }

    protected function addSetterConfigurationFields(FormInterface $form, string $configurationType): void
    {
        $form->add('setterConfig', $configurationType);
    }

    protected function addInterpreterConfigurationFields(FormInterface $form, string $configurationType): void
    {
        $form->add('interpreterConfig', $configurationType);
    }

    /**
     * @param mixed $data
     */
    protected function getSetterRegistryIdentifier(FormInterface $form, $data = null): ?string
    {
        if (null !== $data && null !== $data->getSetter()) {
            return $data->getSetter();
        }

        return null;
    }

    /**
     * @param mixed $data
     */
    protected function getInterpreterRegistryIdentifier(FormInterface $form, $data = null): ?string
    {
        if (null !== $data && null !== $data->getInterpreter()) {
            return $data->getInterpreter();
        }

        return null;
    }
}
