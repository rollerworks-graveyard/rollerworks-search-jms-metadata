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

use Rollerworks\Component\Search\Metadata\Driver\YamlFileDriver;

final class YamlFileDriverTest extends MetadataFileDriverTestCase
{
    protected function getDriver()
    {
        return new YamlFileDriver($this->getFileLocator());
    }

    protected function getFailureException()
    {
        return array(
            'Rollerworks\Component\Search\Exception\InvalidArgumentException',
            '#No "type" found in property metadata of class ".+Client" property "name", loaded '.
            'from file ".+/Config/Entity\.Client\.yml"#i'
        );
    }
}
