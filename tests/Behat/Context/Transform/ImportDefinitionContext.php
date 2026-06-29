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
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\DataDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Repository\DefinitionRepository;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Behat\Service\SharedStorageInterface;

final readonly class ImportDefinitionContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private DefinitionRepository $definitionRepository
    ) {
    }

    /**
     * @Transform /^import-definition "([^"]+)"$/
     */
    public function definitionWithName(string $name): DataDefinitionInterface
    {
        $all = $this->definitionRepository->findAll();

        /**
         * @var DataDefinitionInterface $definition
         */
        foreach ($all as $definition) {
            if ($definition->getName() === $name) {
                return $definition;
            }
        }

        throw new \InvalidArgumentException(sprintf('Definition with name %s not found', $name));
    }

    /**
     * @Transform /^import-definition$/
     * @Transform /^import-definitions$/
     */
    public function definition(): ?DataDefinitionInterface
    {
        return $this->sharedStorage->get('import-definition');
    }
}
