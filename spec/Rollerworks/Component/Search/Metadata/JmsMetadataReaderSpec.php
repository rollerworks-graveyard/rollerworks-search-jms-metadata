<?php

/**
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) 2012-2014 Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\Rollerworks\Component\Search\Metadata;

use Metadata\ClassMetadata;
use Metadata\MetadataFactoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rollerworks\Component\Search\Metadata\PropertyMetadata;
use Rollerworks\Component\Search\Metadata\SearchField;

class JmsMetadataReaderSpec extends ObjectBehavior
{
    function let(MetadataFactoryInterface $metadataFactory)
    {
        $this->beConstructedWith($metadataFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rollerworks\Component\Search\Metadata\JmsMetadataReader');
    }

    function its_a_metadata_reader()
    {
        $this->shouldHaveType('Rollerworks\Component\Search\Metadata\MetadataReaderInterface');
    }

    function it_returns_registered_fields(MetadataFactoryInterface $metadataFactory, ClassMetadata $classMetadata, PropertyMetadata $field)
    {
        $field->class = 'stdClass';
        $field->name = 'id';
        $field->fieldName = 'user_id';
        $field->type = 'integer';
        $field->required = false;
        $field->options = array();

        $classMetadata->propertyMetadata = array('id' => $field);
        $metadataFactory->getMetadataForClass('stdClass')->willReturn($classMetadata);

        $searchField = new SearchField('user_id', 'stdClass', 'id', false, 'integer', array());
        $this->getSearchFields('stdClass')->shouldBeLike(array('user_id' => $searchField));
    }

    function it_returns_a_registered_field(MetadataFactoryInterface $metadataFactory, ClassMetadata $classMetadata, PropertyMetadata $field)
    {
        $field->class = 'stdClass';
        $field->name = 'id';
        $field->fieldName = 'user_id';
        $field->type = 'integer';
        $field->required = false;
        $field->options = array();

        $classMetadata->propertyMetadata = array('id' => $field);
        $metadataFactory->getMetadataForClass('stdClass')->willReturn($classMetadata);

        $searchField = new SearchField('user_id', 'stdClass', 'id', false, 'integer', array());
        $this->getSearchField('stdClass', 'user_id')->shouldBeLike($searchField);
    }
}
