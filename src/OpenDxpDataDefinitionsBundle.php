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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle;

use Instride\Bundle\OpenDxpCampaignsBundle\DependencyInjection\OpenDxpCampaignsExtension;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\CleanerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\ExportProviderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\ExportRunnerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\FetcherRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\FilterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\GetterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\InterpreterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\LoaderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\PersisterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\ProviderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\RemoveEcommerceClassesPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\RunnerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\Compiler\SetterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DependencyInjection\OpenDxpDataDefinitionsExtension;
use OpenDxp\Extension\Bundle\AbstractOpenDxpBundle;
use OpenDxp\Extension\Bundle\Installer\InstallerInterface;
use OpenDxp\Extension\Bundle\OpenDxpBundleAdminClassicInterface;
use OpenDxp\Extension\Bundle\Traits\BundleAdminClassicTrait;
use OpenDxp\Extension\Bundle\Traits\PackageVersionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class OpenDxpDataDefinitionsBundle extends AbstractOpenDxpBundle implements OpenDxpBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;
    use PackageVersionTrait;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RemoveEcommerceClassesPass());
        $container->addCompilerPass(new CleanerRegistryCompilerPass());
        $container->addCompilerPass(new FilterRegistryCompilerPass());
        $container->addCompilerPass(new InterpreterRegistryCompilerPass());
        $container->addCompilerPass(new ProviderRegistryCompilerPass());
        $container->addCompilerPass(new RunnerRegistryCompilerPass());
        $container->addCompilerPass(new SetterRegistryCompilerPass());
        $container->addCompilerPass(new LoaderRegistryCompilerPass());
        $container->addCompilerPass(new GetterRegistryCompilerPass());
        $container->addCompilerPass(new FetcherRegistryCompilerPass());
        $container->addCompilerPass(new ExportProviderRegistryCompilerPass());
        $container->addCompilerPass(new ExportRunnerRegistryCompilerPass());
        $container->addCompilerPass(new PersisterRegistryCompilerPass());
    }

    public function getNiceName(): string
    {
        return 'OpenDXP Data Definitions';
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new OpenDxpDataDefinitionsExtension();
        }

        return $this->extension ?: null;
    }

    public function getInstaller(): ?InstallerInterface
    {
        return $this->container->get(Installer::class);
    }

    public function getCssPaths(): array
    {
        return [
            '/bundles/opendxpdatadefinitions/opendxp/css/styles.css',
        ];
    }

    public function getJsPaths(): array
    {
        $defaultPaths = [
            '/bundles/opendxpdatadefinitions/opendxp/js/startup.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/definition/abstractItem.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/import/panel.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/import/item.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/import/configDialog.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/export/panel.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/export/item.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/export/configDialog.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/export/fields.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/provider/abstractprovider.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/provider/csv.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/provider/excel.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/provider/sql.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/provider/externalSql.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/provider/json.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/provider/xml.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/provider/raw.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/export_provider/abstractprovider.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/export_provider/csv.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/export_provider/xml.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/resource/definition.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/abstract.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/href.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/multihref.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/defaultvalue.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/specificobject.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/assetbypath.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/asseturl.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/assetsurl.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/quantityvalue.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/nested.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/nestedcontainer.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/empty.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/expression.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/objectresolver.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/mapping.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/iterator.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/definition.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/conditional.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/twig.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/carbon.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/metadata.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/interpreters/typecasting.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/setters/abstract.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/setters/fieldcollection.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/setters/objectbrick.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/setters/classificationstore.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/setters/localizedfield.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/getters/fieldcollection.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/getters/objectbrick.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/getters/classificationstore.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/getters/localizedfield.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/fetchers/abstract.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/fetchers/objects.js',
            '/bundles/opendxpdatadefinitions/opendxp/js/automap/fuse.min.js',
        ];

        // Ecommerce interpreter/setter/getter widgets only work with the ecommerce
        // bundle present, mirroring the conditional opendxp_ecommerce.yaml service load.
        $bundles = $this->container?->hasParameter('kernel.bundles') ? $this->container->getParameter('kernel.bundles') : [];
        if (is_array($bundles) && array_key_exists('EcommerceStoreBundle', $bundles)) {
            $defaultPaths = array_merge($defaultPaths, [
                '/bundles/opendxpdatadefinitions/opendxp/js/ecommerce/interpreter/money.js',
                '/bundles/opendxpdatadefinitions/opendxp/js/ecommerce/interpreter/price.js',
                '/bundles/opendxpdatadefinitions/opendxp/js/ecommerce/interpreter/stores.js',
                '/bundles/opendxpdatadefinitions/opendxp/js/ecommerce/setter/storePrice.js',
                '/bundles/opendxpdatadefinitions/opendxp/js/ecommerce/setter/storeValues.js',
                '/bundles/opendxpdatadefinitions/opendxp/js/ecommerce/getter/storePrice.js',
                '/bundles/opendxpdatadefinitions/opendxp/js/ecommerce/getter/storeValues.js',
            ]);
        }

        // Merge with custom JS paths from configuration
        if ($this->container && $this->container->hasParameter('data_definitions.opendxp_admin.js')) {
            $customJsPaths = $this->container->getParameter('data_definitions.opendxp_admin.js');

            if (is_array($customJsPaths)) {
                return array_merge($defaultPaths, array_values($customJsPaths));
            }
        }

        return $defaultPaths;
    }

    protected function getComposerPackageName(): string
    {
        return 'instride/opendxp-data-definitions';
    }
}
