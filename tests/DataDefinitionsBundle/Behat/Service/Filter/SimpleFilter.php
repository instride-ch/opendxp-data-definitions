<?php

namespace Instride\Bundle\DataDefinitionsBundle\Behat\Service\Filter;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Context\FilterContextInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\DataDefinitionsBundle\Filter\FilterInterface;

class SimpleFilter implements FilterInterface
{
    public function filter(FilterContextInterface $context): bool
    {
        return $context->getDataRow()['doFilter'] !== '1';
    }
}
