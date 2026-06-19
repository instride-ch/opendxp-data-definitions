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

namespace Instride\Bundle\DataDefinitionsBundle\DependencyInjection;

use Exception;
use GuzzleHttp\Psr7\HttpFactory;
use OpenDxp\Config\LocationAwareConfigRepository;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class DataDefinitionsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('kernel.bundles');
        $ecommerceAvailable = array_key_exists('EcommerceCoreBundle', $bundles);

        if (array_key_exists('EcommerceStoreBundle', $bundles)) {
            $loader->load('opendxp_ecommerce.yml');
        }

        $loader->load('services.yml');

        if (class_exists(HttpFactory::class)) {
            $loader->load('guzzle_psr7.yml');
        }

        $container->setParameter('data_definitions.config_location', $config['config_location'] ?? []);

        // Register opendxp_admin configuration
        $opendxpAdminConfig = $config['opendxp_admin'] ?? [];
        $container->setParameter('data_definitions.opendxp_admin.js', $opendxpAdminConfig['js'] ?? []);
        $container->setParameter('data_definitions.opendxp_admin.css', $opendxpAdminConfig['css'] ?? []);

        $importDefinitions = $config['import_definitions'];
        $exportDefinitions = $config['export_definitions'];

        if (!$ecommerceAvailable) {
            $importDefinitions = $this->filterEcommerceDefinitions($importDefinitions);
            $exportDefinitions = $this->filterEcommerceDefinitions($exportDefinitions);
        }

        $container->setParameter('data_definitions.import_definitions', $importDefinitions);
        $container->setParameter('data_definitions.export_definitions', $exportDefinitions);
    }

    public function prepend(ContainerBuilder $container): void
    {
        LocationAwareConfigRepository::loadSymfonyConfigFiles($container, 'data_definitions', 'export_definitions');
        LocationAwareConfigRepository::loadSymfonyConfigFiles($container, 'data_definitions', 'import_definitions');

        if (class_exists(JMS\SerializerBundle\JMSSerializerBundle::class)) {
            $container->prependExtensionConfig('jms_serializer', [
                'metadata' => [
                    'directories' => [
                        'data-definitions' => [
                            'namespace_prefix' => 'DataDefinitionsBundle',
                            'path' => '@DataDefinitionsBundle/Resources/config/serializer',
                        ],
                    ],
                ],
            ]);
        }
    }

    private function filterEcommerceDefinitions(array $definitions): array
    {
        return array_filter($definitions, function ($definition) {
            if (is_string($definition)) {
                return !str_contains($definition, 'Ecommerce');
            }
            if (is_array($definition) && isset($definition['class'])) {
                return !str_contains($definition['class'], 'Ecommerce');
            }
            return true;
        });
    }
}
