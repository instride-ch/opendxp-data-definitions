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
 * @license   https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Controller;

use Instride\Bundle\DataDefinitionsBundle\Repository\DefinitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractDefinitionController extends AbstractController
{
    protected DefinitionRepository $repository;

    abstract protected function getListingClass(): string;

    abstract protected function getModelClass(): string;

    public function __construct(DefinitionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAction(Request $request): JsonResponse
    {
        $this->isGrantedOr403();

        $resources = $this->findOr404((string) $request->get('id'));

        $data = $resources;
        if (method_exists($resources, 'isWriteable')) {
            $data->isWriteable = $resources->isWriteable();
        } else {
            $data->isWriteable = true;
        }

        return $this->json(['data' => $data, 'success' => true]);
    }

    public function listAction(Request $request): JsonResponse
    {
        $this->isGrantedOr403();

        // TODO Miguel - replace deprecated methods
        $start = (int) $request->get('start', 0);
        $limit = (int) $request->get('limit', 25);
        $sort = $request->get('sort', 'id');
        $dir = $request->get('dir', 'ASC');
        $filter = $request->get('filter', '');

        $listingClass = $this->getListingClass();
        $list = new $listingClass();

        if (!empty($filter)) {
            $list->setCondition("name LIKE ?", ["%$filter%"]);
        }

        $list->setOrderKey($sort);
        $list->setOrder($dir);
        $list->setLimit($limit);
        $list->setOffset($start);

        $total = $list->getTotalCount();
        $data = $list->load();

        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'class' => $item->getClass(),
            ];
        }

        return $this->json([
            'data' => $result,
            'success' => true,
            'total' => $total,
        ]);
    }

    public function saveAction(Request $request): JsonResponse
    {
        $this->isGrantedOr403();

        $data = json_decode($request->getContent(), true);
        $id = $data['id'] ?? null;

        if ($id) {
            $definition = $this->findOr404((string) $id);
        } else {
            $modelClass = $this->getModelClass();
            $definition = new $modelClass();
        }

        $definition->setValues($data);
        $definition->save();

        return $this->json([
            'data' => $definition,
            'success' => true,
        ]);
    }

    public function deleteAction(Request $request): JsonResponse
    {
        $this->isGrantedOr403();

        // TODO Miguel - replace deprecated methods
        $definition = $this->findOr404((string) $request->get('id'));
        $definition->delete();

        return $this->json(['success' => true]);
    }

    protected function isGrantedOr403(): void
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException('Access denied.');
        }
    }

    protected function findOr404(string $id): object
    {
        $resource = $this->repository->find($id);

        if (!$resource) {
            throw new NotFoundHttpException(sprintf('Resource with ID "%s" not found.', $id));
        }

        return $resource;
    }
}
