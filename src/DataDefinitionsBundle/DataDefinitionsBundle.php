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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\CleanerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\ExportProviderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\ExportRunnerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\FetcherRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\FilterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\GetterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\ImportRuleActionPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\ImportRuleConditionPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\InterpreterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\LoaderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\PersisterRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\ProviderRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\RunnerRegistryCompilerPass;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\DependencyInjection\Compiler\SetterRegistryCompilerPass;
use OpenDxp\Extension\Bundle\AbstractOpenDxpBundle;
use OpenDxp\Extension\Bundle\Installer\InstallerInterface;
use OpenDxp\Extension\Bundle\Traits\BundleAdminClassicTrait;
use OpenDxp\Extension\Bundle\Traits\PackageVersionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DataDefinitionsBundle extends AbstractOpenDxpBundle
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
        return [];
    }

    public function getJsPaths(): array
    {
        return [];
    }

    protected function getComposerPackageName(): string
    {
        return 'instride/opendxp-data-definitions';
    }
}
