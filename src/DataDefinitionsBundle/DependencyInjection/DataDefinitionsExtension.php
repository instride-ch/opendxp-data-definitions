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

namespace Instride\Bundle\DataDefinitionsBundle\DependencyInjection;

use Instride\Bundle\DataDefinitionsBundle\Cleaner\CleanerInterface;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\CleanerRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\ExportProviderRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\ExportRunnerRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\FetcherRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\FilterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\GetterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\InterpreterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\LoaderRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\PersisterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\ProviderRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\RunnerRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\SetterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\Fetcher\FetcherInterface;
use Instride\Bundle\DataDefinitionsBundle\Filter\FilterInterface;
use Instride\Bundle\DataDefinitionsBundle\Getter\GetterInterface;
use Instride\Bundle\DataDefinitionsBundle\Interpreter\InterpreterInterface;
use Instride\Bundle\DataDefinitionsBundle\Loader\LoaderInterface;
use Instride\Bundle\DataDefinitionsBundle\Persister\PersisterInterface;
use Instride\Bundle\DataDefinitionsBundle\Provider\ExportProviderInterface;
use Instride\Bundle\DataDefinitionsBundle\Provider\ImportProviderInterface;
use Instride\Bundle\DataDefinitionsBundle\Runner\ExportRunnerInterface;
use Instride\Bundle\DataDefinitionsBundle\Runner\RunnerInterface;
use Instride\Bundle\DataDefinitionsBundle\Setter\SetterInterface;
use OpenDxp\Config\LocationAwareConfigRepository;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class DataDefinitionsExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

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
