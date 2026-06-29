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

namespace Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Exception;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Service\ClassStorageInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Service\SharedStorageInterface;
use OpenDxp\Cache\RuntimeCache;
use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\ClassDefinition;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Fieldcollection\Definition;
use Webmozart\Assert\Assert;

final readonly class OpenDxpClassContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private ClassStorageInterface  $classStorage
    )
    {
    }

    /**
     * @Transform /^class "([^"]+)"$/
     * @throws Exception
     */
    public function class(string $name): ClassDefinition
    {
        RuntimeCache::clear();

        $name = $this->classStorage->get($name);

        $classDefinition = ClassDefinition::getByName($name);

        Assert::notNull($classDefinition, sprintf('Class Definition for class with name %s not found', $name));

        return $classDefinition;
    }

    /**
     * @Transform /^field-collection "([^"]+)"$/
     * @throws Exception
     */
    public function fieldCollection(string $name): Definition
    {
        $name = $this->classStorage->get($name);

        $definition = Definition::getByKey($name);

        Assert::notNull($definition, sprintf('Definition for fieldcollection with key %s not found', $name));

        return $definition;
    }

    /**
     * @Transform /^object-instance$/
     */
    public function objectInstance(): Concrete
    {
        return $this->sharedStorage->get('object-instance');
    }

    /**
     * @Transform /^object-instance "([^"]+)"$/
     */
    public function objectInstanceWithKey(string $key): Concrete
    {
        return Concrete::getByPath('/' . $key);
    }

    /**
     * @Transform /^object of the definition$/
     */
    public function objectOfTheDefinition(): DataObject
    {
        $definition = $this->definition();

        /**
         * @var class-string $fqcn
         */
        $fqcn = 'OpenDxp\Model\DataObject\\' . ucfirst($definition->getName());

        /**
         * @var DataObject\Listing $list
         */
        $list = $fqcn::getList();
        $list->setUnpublished(true);

        Assert::eq(1, $list->getTotalCount(), 'Can only find one object, but the list contains more or none');

        return $list->getObjects()[0];
    }

    /**
     * @Transform /^object of class "([^"]+)"$/
     * @throws Exception
     */
    public function objectOfTheClass(string $name): DataObject
    {
        $definition = $this->class($name);

        $fqcn = 'OpenDxp\Model\DataObject\\' . ucfirst($definition->getName());

        /**
         * @var DataObject\Listing $list
         */
        if (method_exists($fqcn, 'getList')) {
            $list = $fqcn::getList();

            $list->setUnpublished(true);

            Assert::eq(1, $list->getTotalCount(), 'Can only find one object, but the list contains more or none');
            return $list->getObjects()[0];
        }

        throw new Exception('Can only find one object, but the list contains more or none');
    }

    /**
     * @Transform /^definition/
     * @Transform /^definitions/
     * @throws Exception
     */
    public function definition(): ClassDefinition
    {
        RuntimeCache::clear();

        $name = $this->sharedStorage->get('pimcore_definition_name');
        $class = $this->sharedStorage->get('pimcore_definition_class');

        if ($class === ClassDefinition::class) {
            return ClassDefinition::getByName($this->classStorage->get($name));
        }

        return $class::getByKey($this->classStorage->get($name));
    }
}
