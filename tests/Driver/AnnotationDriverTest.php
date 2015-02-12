<?php

/*
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Rollerworks\Component\Search\Metadata\Driver\AnnotationDriver;
use Rollerworks\Component\Search\Tests\Metadata\MetadataDriverTestCase;

class AnnotationDriverTest extends MetadataDriverTestCase
{
    protected function getDriver()
    {
        return new AnnotationDriver(new AnnotationReader());
    }

    protected function getFailureException()
    {
        return array(
            'Rollerworks\Component\Search\Exception\InvalidArgumentException',
            '#'.
            preg_quote('Property "type" on annotation "Rollerworks\Component\Search\Mapping\Field" is required', '#').
            '#i'
        );
    }
}
