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

namespace Instride\Bundle\DataDefinitionsBundle\Fetcher;

use Instride\Bundle\DataDefinitionsBundle\Context\FetcherContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ExportDefinitionInterface;
use InvalidArgumentException;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\ClassDefinition;
use OpenDxp\Model\DataObject\Listing;

class ObjectsFetcher implements FetcherInterface
{
    protected Listing $list;

    public function fetch(FetcherContextInterface $context, int $limit, int $offset): array
    {
        $list = $this->getClassListing($context->getDefinition(), $context->getParams());
        $list->setLimit($limit);
        $list->setOffset($offset);

        return $list->load();
    }

    public function count(FetcherContextInterface $context): int
    {
        return $this->getClassListing($context->getDefinition(), $context->getParams())->getTotalCount();
    }

    protected function filterQueryParam(string $query): string
    {
        if ($query === '*') {
            $query = '';
        }

        $query = str_replace(['%', '@'], ['*', '#'], $query);
        $query = preg_replace("@([^ ])\-@", '$1 ', $query);

        $query = str_replace(['<', '>', '(', ')', '~'], ' ', $query);

        // it is not allowed to have * behind another *
        $query = preg_replace('#[*]+#', '*', $query);

        // no boolean operators at the end of the query
        $query = rtrim($query, '+- ');

        return $query;
    }

    private function getClassListing(ExportDefinitionInterface $definition, array $params): Listing
    {
        if (isset($this->list)) {
            return $this->list;
        }

        $class = $definition->getClass();

        try {
            $classDefinition = ClassDefinition::getByName($class);
        } catch (\Exception $e) {
            throw new InvalidArgumentException(sprintf('Error getting Class for classname: %s', $class));
        }

        if (!$classDefinition instanceof ClassDefinition) {
            throw new InvalidArgumentException(sprintf('Class not found %s', $class));
        }

        $classList = '\OpenDxp\Model\DataObject\\' . ucfirst($class) . '\Listing';
        $list = new $classList();
        $list->setUnpublished($definition->isFetchUnpublished());

        $rootNode = null;
        $conditionFilters = [];
        if (isset($params['root'])) {
            $rootNode = AbstractObject::getById($params['root']);

            if (null !== $rootNode) {
                $quotedPath = $list->quote($rootNode->getRealFullPath());
                $quotedWildcardPath = $list->quote(str_replace('//', '/', $rootNode->getRealFullPath() . '/') . '%');
                $conditionFilters[] = '(path = ' . $quotedPath . ' OR path LIKE ' . $quotedWildcardPath . ')';
            }
        }

        if (isset($params['query'])) {
            $query = $this->filterQueryParam($params['query']);
            if (!empty($query)) {
                $conditionFilters[] = 'oo_id IN (SELECT id FROM search_backend_data WHERE MATCH (`data`,`properties`) AGAINST (' . $list->quote(
                    $query,
                ) . ' IN BOOLEAN MODE))';
            }
        }

        if (isset($params['only_direct_children']) && $params['only_direct_children'] == 'true' && null !== $rootNode) {
            $conditionFilters[] = 'parentId = ' . $rootNode->getId();
        }

        if (isset($params['condition'])) {
            $conditionFilters[] = '(' . $params['condition'] . ')';
        }
        if (isset($params['ids'])) {
            $quotedIds = [];
            foreach ($params['ids'] as $id) {
                $quotedIds[] = $list->quote($id);
            }
            if (!empty($quotedIds)) {
                $conditionFilters[] = 'oo_id IN (' . implode(',', $quotedIds) . ')';
            }
        }

        $list->setCondition(implode(' AND ', $conditionFilters));

        // ensure a stable sort across pages
        $list->setOrderKey('id');
        $list->setOrder('asc');

        return $this->list = $list;
    }
}
