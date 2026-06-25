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
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @copyright  Modification Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Instride\Bundle\DataDefinitionsBundle\Controller;

use Exception;
use Instride\Bundle\DataDefinitionsBundle\Form\Type\ImportDefinitionType;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinition;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportMapping;
use Instride\Bundle\DataDefinitionsBundle\Model\ImportMapping\FromColumn;
use Instride\Bundle\DataDefinitionsBundle\Registry\ServiceRegistry;
use Instride\Bundle\DataDefinitionsBundle\Service\FieldSelection;
use function is_array;
use OpenDxp\Model\DataObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Service\Attribute\SubscribedService;

class ImportDefinitionController extends AbstractDefinitionController
{
    public function addAction(Request $request): JsonResponse
    {
        $this->isGrantedOr403();

        $name = $request->get('name');
        if (empty($name)) {
            throw new \Exception('Name is required and can not be empty');
        }

        $definition = new ImportDefinition();
        $definition->setName($name);
        $definition->save();

        return $this->json([
            'data' => $definition,
            'success' => true,
        ]);
    }

    public function getConfigAction(): JsonResponse
    {
        $providers = $this->getConfigProviders();
        $loaders = $this->getConfigLoaders();
        $interpreters = $this->getConfigInterpreters();
        $cleaners = $this->getConfigCleaners();
        $setters = $this->getConfigSetters();
        $filters = $this->getConfigFilters();
        $runners = $this->getConfigRunners();
        $persisters = $this->getConfigPersisters();

        return $this->json([
            'providers' => array_values($providers),
            'loaders' => array_values($loaders),
            'interpreter' => array_values($interpreters),
            'cleaner' => array_values($cleaners),
            'setter' => array_values($setters),
            'filters' => array_values($filters),
            'runner' => array_values($runners),
            'persister' => array_values($persisters),
        ]);
    }

    public function testDataAction(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $definition = $this->repository->find($id);

        if ($definition instanceof ImportDefinitionInterface) {
            try {
                if ($this->container->get('data_definitions.registry.provider')->get(
                    $definition->getProvider(),
                )->testData(
                    $definition->getConfiguration(),
                )) {
                    return $this->json(['success' => true]);
                }
            } catch (Exception $ex) {
                return $this->json(['success' => false, 'message' => $ex->getMessage()]);
            }
        }

        return $this->json(['success' => false]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getColumnsAction(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $definition = $this->repository->find($id);

        if ($definition instanceof ImportDefinitionInterface && $definition->getClass()) {
            $customFromColumn = new FromColumn();
            $customFromColumn->setIdentifier('custom');
            $customFromColumn->setLabel('Custom');

            try {
                $fromColumns = $this->container->get('data_definitions.registry.provider')->get(
                    $definition->getProvider(),
                )->getColumns($definition->getConfiguration());
                $fromColumns[] = $customFromColumn;
            } catch (Exception $e) {
                $fromColumns = [];
            }

            try {
                $classDefinition = DataObject\ClassDefinition::getByName($definition->getClass());
            } catch (Exception $e) {
                throw new \RuntimeException(sprintf("Couldn't load definition for class: %s. Exception: %s", $definition->getClass(), $e->getMessage()));
            }

            if (!$classDefinition) {
                throw new NotFoundHttpException(sprintf("Couldn't load definition for class: %s", $definition->getClass()));
            }

            $toColumns = $this->container->get(FieldSelection::class)->getClassDefinition($classDefinition);
            $mappings = $definition->getMapping();
            $mappingDefinition = [];
            $fromColumnsResult = [];
            $bricks = [];
            $collections = [];

            foreach ($classDefinition->getFieldDefinitions() as $field) {
                if ($field instanceof DataObject\ClassDefinition\Data\Objectbricks) {
                    $bricks[$field->getName()] = $field->getAllowedTypes();
                } elseif ($field instanceof DataObject\ClassDefinition\Data\Fieldcollections) {
                    $collections[$field->getName()] = $field->getAllowedTypes();
                }
            }

            foreach ($fromColumns as $fromColumn) {
                $fromColumn = get_object_vars($fromColumn);
                $fromColumn['id'] = $fromColumn['identifier'];
                $fromColumnsResult[] = $fromColumn;
            }

            foreach ($toColumns as $classToColumn) {
                $found = false;

                if (is_array($mappings)) {
                    /**
                     * @var ImportMapping $mapping
                     */
                    foreach ($mappings as $mapping) {
                        if ($mapping->getToColumn() === $classToColumn->getIdentifier()) {
                            $found = true;

                            $mappingDefinition[] = [
                                'fromColumn' => $mapping->getFromColumn(),
                                'toColumn' => $mapping->getToColumn(),
                                'primaryIdentifier' => $mapping->getPrimaryIdentifier(),
                                'setter' => $mapping->getSetter(),
                                'setterConfig' => $mapping->getSetterConfig(),
                                'interpreter' => $mapping->getInterpreter(),
                                'interpreterConfig' => $mapping->getInterpreterConfig(),
                            ];

                            break;
                        }
                    }
                }

                if (!$found) {
                    $mappingDefinition[] = [
                        'identifier' => null,
                        'fromColumn' => null,
                        'toColumn' => $classToColumn->getIdentifier(),
                        'primaryIdentifier' => false,
                        'config' => $classToColumn->getConfig(),
                        'setter' => $classToColumn->getSetter(),
                        'setterConfig' => $classToColumn->getSetterConfig(),
                        'interpreter' => $classToColumn->getInterpreter(),
                        'interpreterConfig' => $classToColumn->getInterpreterConfig(),
                    ];
                }
            }

            return $this->json([
                'success' => true,
                'mapping' => $mappingDefinition,
                'fromColumns' => $fromColumnsResult,
                'toColumns' => $toColumns,
                'bricks' => $bricks,
                'fieldcollections' => $collections,
            ]);
        }

        return $this->json(['success' => false]);
    }

    public function exportAction(Request $request): Response
    {
        $id = (int) $request->get('id');

        if ($id) {
            $definition = $this->repository->find($id);

            if ($definition instanceof ImportDefinitionInterface) {
                $name = $definition->getName();
                unset($definition->id, $definition->creationDate, $definition->modificationDate);

                $response = new Response();
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set(
                    'Content-Disposition',
                    sprintf('attachment; filename="import-definition-%s.json"', $name),
                );
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
                $response->headers->set('Content-Transfer-Encoding', 'binary');

                $response->setContent(json_encode($definition));

                return $response;
            }
        }

        throw new NotFoundHttpException();
    }

    public function importAction(Request $request): JsonResponse
    {
        $id = (int) $request->get('id');
        $definition = $this->repository->find($id);

        if ($id && $definition instanceof ImportDefinitionInterface && $request->files->has('Filedata')) {
            $uploadedFile = $request->files->get('Filedata');

            if ($uploadedFile instanceof UploadedFile) {
                $jsonContent = file_get_contents($uploadedFile->getPathname());
                $data = $this->decodeJson($jsonContent, false, [], false);

                $form = $this->createForm(ImportDefinitionType::class, $definition);
                $handledForm = $form->submit($data);

                if ($handledForm->isValid()) {
                    $definition = $handledForm->getData();
                    $definition->save();

                    return $this->json(['success' => true]);
                }
            }
        }

        return $this->json(['success' => false]);
    }

    public function duplicateAction(Request $request): JsonResponse
    {
        $id = (int) $request->get('id');
        $definition = $this->repository->find($id);
        $name = (string) $request->get('name');

        if ($definition instanceof ImportDefinitionInterface && $name) {
            $newDefinition = clone $definition;
            $newDefinition->setId(null);
            $newDefinition->setName($name);
            $newDefinition->save();

            return $this->json(['success' => true, 'data' => $newDefinition]);
        }

        return $this->json(['success' => false]);
    }

    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices() + [
                FieldSelection::class,
                new SubscribedService('data_definitions.registry.provider', ServiceRegistry::class, attributes: new Autowire(service: 'data_definitions.registry.provider')),
            ];
    }

    protected function getListingClass(): string
    {
        return \Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinition\Listing::class;
    }

    protected function getModelClass(): string
    {
        return \Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinition::class;
    }

    protected function getConfigProviders(): array
    {
        return $this->getParameter('data_definitions.import_providers');
    }

    protected function getConfigLoaders(): array
    {
        return $this->getParameter('data_definitions.loaders');
    }

    protected function getConfigInterpreters(): array
    {
        return $this->getParameter('data_definitions.interpreters');
    }

    protected function getConfigCleaners(): array
    {
        return $this->getParameter('data_definitions.cleaners');
    }

    protected function getConfigSetters(): array
    {
        return $this->getParameter('data_definitions.setters');
    }

    protected function getConfigFilters(): array
    {
        return $this->getParameter('data_definitions.filters');
    }

    protected function getConfigRunners(): array
    {
        return $this->getParameter('data_definitions.runners');
    }

    protected function getConfigPersisters(): array
    {
        return $this->getParameter('data_definitions.persisters');
    }
}
