<?php

/*
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search\Tests\Metadata;

use Metadata\Driver\AdvancedDriverInterface;
use Metadata\MetadataFactory;
use Rollerworks\Component\Search\Metadata\JmsMetadataReader;
use Rollerworks\Component\Search\Metadata\SearchField;
use Rollerworks\Component\Search\Searches;

abstract class MetadataDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JmsMetadataReader
     */
    private $reader;

    protected function setUp()
    {
        $metadataFactory = new MetadataFactory($this->getDriver());
        $this->reader = new JmsMetadataReader($metadataFactory);

        // Ensure the loader is compatible
        Searches::createSearchFactoryBuilder()
            ->setMetaReader($this->reader)
            ->getSearchFactory()
        ;
    }

    /**
     * @return DriverInterface
     */
    abstract protected function getDriver();

    /**
     * @return array array with [FQC exception class, message regexp]
     */
    abstract protected function getFailureException();

    /**
     * @test
     */
    public function it_returns_a_registered_field()
    {
        $userClass = 'Rollerworks\Component\Search\Tests\Metadata\Fixtures\Entity\User';
        $groupClass = 'Rollerworks\Component\Search\Tests\Metadata\Fixtures\Entity\Group';

        $this->assertEquals(
            new SearchField('uid', $userClass, 'id', true, 'integer', array()),
            $this->reader->getSearchField($userClass, 'uid')
        );

        $this->assertEquals(
            new SearchField('username', $userClass, 'name', false, 'text', array(
                'name' => 'doctor',
                'last' => array('who', 'zeus'),
            )),
            $this->reader->getSearchField($userClass, 'username')
        );

        // Group
        $this->assertNull($this->reader->getSearchField($groupClass, 'id'));
        $this->assertNull($this->reader->getSearchField($groupClass, 'name'));
    }

    /**
     * @test
     */
    public function it_gets_all_classes()
    {
        $driver = $this->getDriver();

        if (!$driver instanceof AdvancedDriverInterface) {
            $this->markTestSkipped(sprintf('Driver "%s" does implement AdvancedDriverInterface,', get_class($driver)));
        }

        $this->assertEquals(
            array(
                'Rollerworks\Component\Search\Tests\Metadata\Fixtures\Entity\Client',
                'Rollerworks\Component\Search\Tests\Metadata\Fixtures\Entity\User',
            ),
            $driver->getAllClassNames()
        );
    }

    /**
     * @test
     */
    public function it_errors_when_data_is_invalid()
    {
        $exceptionInfo = $this->getFailureException();

        $this->setExpectedExceptionRegExp($exceptionInfo[0], $exceptionInfo[1]);
        $this->reader->getSearchFields('Rollerworks\Component\Search\Tests\Metadata\Fixtures\Entity\Client');
    }
}
