<?php

/*
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search\Metadata;

use Metadata\MetadataFactoryInterface;

/**
 * JmsMetadataReader
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
class JmsMetadataReader implements MetadataReaderInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * Constructor.
     *
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * Attempts to read the search fields from a class.
     *
     * @param string $class The class name to test (FQCN).
     *
     * @return SearchField[] An associative array with search fields
     */
    public function getSearchFields($class)
    {
        $fields = array();
        $metadata = $this->metadataFactory->getMetadataForClass($class);

        if (!$metadata) {
            return $fields;
        }

        foreach ($metadata->propertyMetadata as $property => $field) {
            /** @var PropertyMetadata $field */
            $fields[$field->fieldName] = new SearchField(
                $field->fieldName,
                $field->class,
                $property,
                $field->required,
                $field->type,
                $field->options
            );
        }

        return $fields;
    }

    /**
     * Attempts to read the mapping of a specified property.
     *
     * @param string $class The class name to test (FQCN).
     * @param string $field The search field-name
     *
     * @return SearchField|null The field mapping, null when not found
     */
    public function getSearchField($class, $field)
    {
        $fieldsMetadata = $this->getSearchFields($class);

        return isset($fieldsMetadata[$field]) ? $fieldsMetadata[$field] : null;
    }
}
