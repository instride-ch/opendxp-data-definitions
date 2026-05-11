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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection;

use OpenDxp\Bundle\AdminBundle\OpenDxpAdminBundle;
use OpenDxp\Ecommerce\Bundle\CurrencyBundle\EcommerceCurrencyBundle;
use OpenDxp\Ecommerce\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractModelExtension;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Cleaner\CleanerInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\CleanerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\ExportProviderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\ExportRunnerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\FetcherRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\FilterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\GetterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\InterpreterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\LoaderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\PersisterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\ProviderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\RunnerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\SetterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Fetcher\FetcherInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Filter\FilterInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Getter\GetterInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Interpreter\InterpreterInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Loader\LoaderInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Persister\PersisterInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Provider\ExportProviderInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Provider\ImportProviderInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Runner\ExportRunnerInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Runner\RunnerInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Setter\SetterInterface;
use OpenDxp\Bundle\SimpleBackendSearchBundle\opendxpSimpleBackendSearchBundle;
use OpenDxp\Config\LocationAwareConfigRepository;
use OpenDxp\Ecommerce\Bundle\ResourceBundle\EcommerceResourceBundle;
use OpenDxp\Ecommerce\Bundle\RuleBundle\EcommerceRuleBundle;
use OpenDxp\Ecommerce\Bundle\StoreBundle\EcommerceStoreBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class DataDefinitionsExtension extends AbstractModelExtension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerResources('data_definitions', $config['driver'], $config['resources'], $container);

        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('EcommerceStoreBundle', $bundles)) {
            $config['opendxp_admin']['js']['ecommerce_interpreter_price'] = '/bundles/datadefinitions/opendxp/js/ecommerce/interpreter/price.js';
            $config['opendxp_admin']['js']['ecommerce_interpreter_stores'] = '/bundles/datadefinitions/opendxp/js/ecommerce/interpreter/stores.js';
            $config['opendxp_admin']['js']['ecommerce_interpreter_money'] = '/bundles/datadefinitions/opendxp/js/ecommerce/interpreter/money.js';
            $config['opendxp_admin']['js']['ecommerce_setter_storePrice'] = '/bundles/datadefinitions/opendxp/js/ecommerce/setter/storePrice.js';
            $config['opendxp_admin']['js']['ecommerce_getter_storePrice'] = '/bundles/datadefinitions/opendxp/js/ecommerce/getter/storePrice.js';
            $config['opendxp_admin']['js']['ecommerce_setter_store_values'] = '/bundles/datadefinitions/opendxp/js/ecommerce/setter/storeValues.js';
            $config['opendxp_admin']['js']['ecommerce_getter_store_values'] = '/bundles/datadefinitions/opendxp/js/ecommerce/getter/storeValues.js';
            $loader->load('opendxp_ecommerce.yml');
        }

        $loader->load('services.yml');

        if (class_exists(\GuzzleHttp\Psr7\HttpFactory::class)) {
            $loader->load('guzzle_psr7.yml');
        }

        $dependantBundles = [
            OpenDxpSimpleBackendSearchBundle::class,
            OpenDxpAdminBundle::class,
            EcommerceResourceBundle::class,
            EcommerceStoreBundle::class,
            EcommerceRuleBundle::class,
            EcommerceCurrencyBundle::class,
        ];

        $this->registerDependantBundles('ecommerce', $dependantBundles, $container);
        $this->registerOpenDxpResources('data_definitions', $config['opendxp_admin'], $container);

        $container
            ->registerForAutoconfiguration(CleanerInterface::class)
            ->addTag(CleanerRegistryCompilerPass::CLEANER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(ExportProviderInterface::class)
            ->addTag(ExportProviderRegistryCompilerPass::EXPORT_PROVIDER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(ExportRunnerInterface::class)
            ->addTag(ExportRunnerRegistryCompilerPass::EXPORT_RUNNER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(FetcherInterface::class)
            ->addTag(FetcherRegistryCompilerPass::FETCHER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(FilterInterface::class)
            ->addTag(FilterRegistryCompilerPass::FILTER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(GetterInterface::class)
            ->addTag(GetterRegistryCompilerPass::GETTER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(InterpreterInterface::class)
            ->addTag(InterpreterRegistryCompilerPass::INTERPRETER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(LoaderInterface::class)
            ->addTag(LoaderRegistryCompilerPass::LOADER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(ImportProviderInterface::class)
            ->addTag(ProviderRegistryCompilerPass::IMPORT_PROVIDER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(RunnerInterface::class)
            ->addTag(RunnerRegistryCompilerPass::RUNNER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(SetterInterface::class)
            ->addTag(SetterRegistryCompilerPass::SETTER_TAG)
        ;
        $container
            ->registerForAutoconfiguration(PersisterInterface::class)
            ->addTag(PersisterRegistryCompilerPass::PERSISTER_TAG)
        ;

        $container->setParameter('data_definitions.config_location', $config['config_location'] ?? []);

        $container->setParameter('data_definitions.import_definitions', $config['import_definitions']);
        $container->setParameter('data_definitions.export_definitions', $config['export_definitions']);
    }

    public function prepend(ContainerBuilder $container): void
    {
        LocationAwareConfigRepository::loadSymfonyConfigFiles($container, 'data_definitions', 'export_definitions');
        LocationAwareConfigRepository::loadSymfonyConfigFiles($container, 'data_definitions', 'import_definitions');
    }
}
