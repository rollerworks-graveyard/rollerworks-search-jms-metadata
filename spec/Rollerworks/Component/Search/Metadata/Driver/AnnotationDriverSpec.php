<?php

/**
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) 2012-2014 Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\Rollerworks\Component\Search\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Metadata\MergeableClassMetadata;
use PhpSpec\ObjectBehavior;
use Rollerworks\Component\Search\Mapping\Field as AnnotationField;
use Rollerworks\Component\Search\Metadata\PropertyMetadata;

// Initialize the annotation loader
$loader = require __DIR__.'/../../../../../../vendor/autoload.php';
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Autoloading is not possible for this
require_once __DIR__.'/../../../../../Fixtures/Entity/User.php';
require_once __DIR__.'/../../../../../Fixtures/Entity/Group.php';

class AnnotationDriverSpec extends ObjectBehavior
{
    public function let(Reader $reader)
    {
        $this->beConstructedWith($reader);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rollerworks\Component\Search\Metadata\Driver\AnnotationDriver');
        $this->shouldImplement('Metadata\Driver\DriverInterface');
    }

    public function it_reads_the_metadata(Reader $reader, AnnotationField $annotationField, AnnotationField $annotationField2)
    {
        $this->beConstructedWith($reader);

        $annotationField->getName()->willReturn('uid');
        $annotationField->getType()->willReturn('integer');
        $annotationField->isRequired()->willReturn(false);
        $annotationField->getOptions()->willReturn(array());

        $reflection = new \ReflectionProperty('Rollerworks\Component\Search\Metadata\Fixtures\User', 'id');
        $reader->getPropertyAnnotation($reflection, 'Rollerworks\Component\Search\Metadata\Field')->willReturn($annotationField->getWrappedObject());

        $annotationField2->getName()->willReturn('username');
        $annotationField2->getType()->willReturn('text');
        $annotationField2->isRequired()->willReturn(false);
        $annotationField2->getOptions()->willReturn(array());

        $reflection = new \ReflectionProperty('Rollerworks\Component\Search\Metadata\Fixtures\User', 'name');
        $reader->getPropertyAnnotation($reflection, 'Rollerworks\Component\Search\Metadata\Field')->willReturn($annotationField2->getWrappedObject());

        $classMetadata = new MergeableClassMetadata($reflection->class);
        $classMetadata->createdAt = null;
        $classMetadata->reflection = null;

        $propertyMetadata = new PropertyMetadata($reflection->class, 'id');
        $propertyMetadata->reflection = null;
        $propertyMetadata->fieldName = 'uid';
        $propertyMetadata->type = 'integer';
        $classMetadata->addPropertyMetadata($propertyMetadata);

        $propertyMetadata = new PropertyMetadata($reflection->class, 'name');
        $propertyMetadata->reflection = null;
        $propertyMetadata->fieldName = 'username';
        $propertyMetadata->type = 'text';
        $classMetadata->addPropertyMetadata($propertyMetadata);

        $classReflection = new \ReflectionClass('Rollerworks\Component\Search\Metadata\Fixtures\User');
        $this->loadMetadataForClass($classReflection, true)->shouldBeLike($classMetadata);
    }
}
