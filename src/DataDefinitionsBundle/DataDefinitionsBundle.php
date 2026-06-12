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

namespace Instride\Bundle\DataDefinitionsBundle;

use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\CleanerRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\ExportProviderRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\ExportRunnerRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\FetcherRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\FilterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\GetterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\ImportRuleActionPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\ImportRuleConditionPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\InterpreterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\LoaderRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\PersisterRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\ProviderRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\RunnerRegistryCompilerPass;
use Instride\Bundle\DataDefinitionsBundle\DependencyInjection\Compiler\SetterRegistryCompilerPass;
use OpenDxp\Extension\Bundle\AbstractOpenDxpBundle;
use OpenDxp\Extension\Bundle\Installer\InstallerInterface;
use OpenDxp\Extension\Bundle\Traits\BundleAdminClassicTrait;
use OpenDxp\Extension\Bundle\Traits\PackageVersionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OpenDxp\Extension\Bundle\OpenDxpBundleAdminClassicInterface;

class DataDefinitionsBundle extends AbstractOpenDxpBundle implements OpenDxpBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;
    use PackageVersionTrait;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

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
        $container->addCompilerPass(new ImportRuleConditionPass());
        $container->addCompilerPass(new ImportRuleActionPass());
        $container->addCompilerPass(new PersisterRegistryCompilerPass());
    }

    public function getNiceName(): string
    {
        return 'Data Definitions';
    }

    public function getDescription(): string
    {
        return 'Data Definitions allows you to create reusable Definitions for Importing all kinds of data into DataObjects.';
    }

    public function getInstaller(): ?InstallerInterface
    {
        return $this->container->get(Installer::class);
    }

    public function getCssPaths(): array
    {
        return [
            '/bundles/datadefinitions/opendxp/css/datadefinition.css',
        ];
    }

    public function getJsPaths(): array
    {
        return [
            '/bundles/datadefinitions/opendxp/js/startup.js',
            '/bundles/datadefinitions/opendxp/js/definition/abstractItem.js',
            '/bundles/datadefinitions/opendxp/js/import/panel.js',
            '/bundles/datadefinitions/opendxp/js/import/item.js',
            '/bundles/datadefinitions/opendxp/js/import/configDialog.js',
            '/bundles/datadefinitions/opendxp/js/export/panel.js',
            '/bundles/datadefinitions/opendxp/js/export/item.js',
            '/bundles/datadefinitions/opendxp/js/export/configDialog.js',
            '/bundles/datadefinitions/opendxp/js/export/fields.js',
            '/bundles/datadefinitions/opendxp/js/provider/abstractprovider.js',
            '/bundles/datadefinitions/opendxp/js/provider/csv.js',
            '/bundles/datadefinitions/opendxp/js/provider/excel.js',
            '/bundles/datadefinitions/opendxp/js/provider/sql.js',
            '/bundles/datadefinitions/opendxp/js/provider/externalSql.js',
            '/bundles/datadefinitions/opendxp/js/provider/json.js',
            '/bundles/datadefinitions/opendxp/js/provider/xml.js',
            '/bundles/datadefinitions/opendxp/js/provider/raw.js',
            '/bundles/datadefinitions/opendxp/js/export_provider/abstractprovider.js',
            '/bundles/datadefinitions/opendxp/js/export_provider/csv.js',
            '/bundles/datadefinitions/opendxp/js/export_provider/xml.js',
            '/bundles/datadefinitions/opendxp/js/resource/definition.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/abstract.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/href.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/multihref.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/defaultvalue.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/specificobject.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/assetbypath.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/asseturl.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/assetsurl.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/quantityvalue.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/nested.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/nestedcontainer.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/empty.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/expression.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/objectresolver.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/mapping.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/iterator.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/definition.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/conditional.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/twig.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/carbon.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/metadata.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/typecasting.js',
            '/bundles/datadefinitions/opendxp/js/setters/abstract.js',
            '/bundles/datadefinitions/opendxp/js/setters/fieldcollection.js',
            '/bundles/datadefinitions/opendxp/js/setters/objectbrick.js',
            '/bundles/datadefinitions/opendxp/js/setters/classificationstore.js',
            '/bundles/datadefinitions/opendxp/js/setters/localizedfield.js',
            '/bundles/datadefinitions/opendxp/js/getters/fieldcollection.js',
            '/bundles/datadefinitions/opendxp/js/getters/objectbrick.js',
            '/bundles/datadefinitions/opendxp/js/getters/classificationstore.js',
            '/bundles/datadefinitions/opendxp/js/getters/localizedfield.js',
            '/bundles/datadefinitions/opendxp/js/fetchers/abstract.js',
            '/bundles/datadefinitions/opendxp/js/fetchers/objects.js',
            '/bundles/datadefinitions/opendxp/js/automap/fuse.min.js',
            '/bundles/datadefinitions/opendxp/js/import_rule/action.js',
            '/bundles/datadefinitions/opendxp/js/import_rule/condition.js',
            '/bundles/datadefinitions/opendxp/js/import_rule/item.js',
            '/bundles/datadefinitions/opendxp/js/import_rule/panel.js',
            '/bundles/datadefinitions/opendxp/js/interpreters/import_rule.js',
            '/bundles/datadefinitions/opendxp/js/import_rule/conditions/expression.js',
            '/bundles/datadefinitions/opendxp/js/import_rule/actions/expression.js',
            '/bundles/datadefinitions/opendxp/js/import_rule/actions/object.js',
        ];
    }


    protected function getComposerPackageName(): string
    {
        return 'instride/opendxp-data-definitions';
    }
}
