<?php

/*
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search\Tests\Metadata\Fixtures\Entity;

use Rollerworks\Component\Search\Mapping\Field as SearchField;

class Client
{
    /**
     * @SearchField("id", type="integer")
     */
    public $id;

    /**
     * @SearchField("name")
     */
    public $name;
}
