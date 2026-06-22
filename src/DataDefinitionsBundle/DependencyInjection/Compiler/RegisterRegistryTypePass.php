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
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.com)
 * @copyright  Modification Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

abstract class RegisterRegistryTypePass implements CompilerPassInterface
{
    public function __construct(
        private readonly string $registryServiceId,
        private readonly string $formRegistryServiceId,
        private readonly string $registryParameterName,
        private readonly string $tag,
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition($this->registryServiceId) && !$container->hasAlias($this->registryServiceId)) {
            return;
        }

        $registryDefinition = $container->findDefinition($this->registryServiceId);
        $taggedServices = $container->findTaggedServiceIds($this->tag);

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                if (isset($tag['type'])) {
                    $registryDefinition->addMethodCall('register', [$tag['type'], new Reference($id)]);
                }
            }
        }

        if ($container->hasDefinition($this->formRegistryServiceId) || $container->hasAlias($this->formRegistryServiceId)) {
            $formRegistryDefinition = $container->findDefinition($this->formRegistryServiceId);

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $tag) {
                    if (isset($tag['type'], $tag['form-type'])) {
                        $formRegistryDefinition->addMethodCall('add', [$tag['type'], $tag['form-type']]);
                    }
                }
            }
        }

        $types = [];
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                if (isset($tag['type'])) {
                    $types[] = $tag['type'];
                }
            }
        }
        $container->setParameter($this->registryParameterName, $types);
    }
}
