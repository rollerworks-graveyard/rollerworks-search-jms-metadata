<?php

/*
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\Rollerworks\Component\Search\Metadata\Driver;

use Metadata\Driver\AdvancedFileLocatorInterface;
use Metadata\MergeableClassMetadata;
use PhpSpec\ObjectBehavior;
use Rollerworks\Component\Search\Exception\InvalidArgumentException;
use Rollerworks\Component\Search\Metadata\PropertyMetadata;

require __DIR__.'/../../../../../../vendor/autoload.php';

class XmlFileDriverSpec extends ObjectBehavior
{
    public function let(AdvancedFileLocatorInterface $locator)
    {
        $this->beConstructedWith($locator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rollerworks\Component\Search\Metadata\Driver\XmlFileDriver');
        $this->shouldImplement('Metadata\Driver\DriverInterface');
    }

    public function it_reads_the_metadata(AdvancedFileLocatorInterface $locator)
    {
        $this->beConstructedWith($locator);

        $reflection = new \ReflectionClass('Rollerworks\Component\Search\Metadata\Fixtures\User');
        $locator->findFileForClass($reflection, 'xml')->willReturn(__DIR__ . '/../../../../../Fixtures/Config/Entity.User.xml');

        $classMetadata = new MergeableClassMetadata($reflection->name);

        $propertyMetadata = new PropertyMetadata($reflection->name, 'id');
        $propertyMetadata->fieldName = 'uid';
        $propertyMetadata->required = true;
        $propertyMetadata->type = 'integer';
        $classMetadata->addPropertyMetadata($propertyMetadata);

        $propertyMetadata = new PropertyMetadata($reflection->name, 'name');
        $propertyMetadata->fieldName = 'username';
        $propertyMetadata->type = 'text';
        $propertyMetadata->options = array('name' => 'doctor', 'last' => array('who', 'zeus'));

        $classMetadata->addPropertyMetadata($propertyMetadata);

        $this->loadMetadataForClass($reflection)->shouldEqualMetadata($classMetadata);
    }

    public function it_validates_the_metadata(AdvancedFileLocatorInterface $locator)
    {
        $this->beConstructedWith($locator);

        $reflection = new \ReflectionClass('Rollerworks\Component\Search\Metadata\Fixtures\User');
        $locator->findFileForClass($reflection, 'xml')->willReturn($file = __DIR__.'/../../../../../Fixtures/Config/Entity.User-invalid.xml');

        $this->shouldThrow(new InvalidArgumentException(sprintf('Unable to parse file "%s".', $file)))->during('loadMetadataForClass', array($reflection, true));
    }

    public function getMatchers()
    {
        return array(
            'equalMetadata' => array('Rollerworks\Component\Search\Metadata\Spec\MetadataMatcher', 'equalMetadata')
        );
    }
}
