<?php

/*
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search\Metadata\Driver;

use Metadata\MergeableClassMetadata;
use Rollerworks\Component\Search\Exception\InvalidArgumentException;
use Rollerworks\Component\Search\Metadata\PropertyMetadata;
use Rollerworks\Component\Search\Metadata\SimpleXMLElement;
use Rollerworks\Component\Search\Util\XmlUtils;

/**
 * XmlFileDriver.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
class XmlFileDriver extends AbstractFileDriver
{
    /**
     * {@inheritdoc}
     */
    protected function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $xml = $this->parseFile($file);
        $classMetadata = new MergeableClassMetadata($class->name);

        foreach ($xml as $property) {
            $propertyMetadata = $this->parseProperty($class, $property);
            $classMetadata->addPropertyMetadata($propertyMetadata);
        }

        return $classMetadata;
    }

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    protected function getExtension()
    {
        return 'xml';
    }

    /**
     * @param \ReflectionClass $class
     * @param SimpleXMLElement $property
     *
     * @return PropertyMetadata
     */
    private function parseProperty(\ReflectionClass $class, SimpleXMLElement $property)
    {
        $propertyMetadata = new PropertyMetadata($class->name, (string) $property['id']);
        $propertyMetadata->fieldName = (string) $property['name'];
        $propertyMetadata->required = (isset($property['required']) ? XmlUtils::phpize($property['required']) : false);
        $propertyMetadata->type = (string) $property['type'];

        if (isset($property->option)) {
            $propertyMetadata->options = $property->getArgumentsAsPhp('option');
        }

        return $propertyMetadata;
    }

    /**
     * Parses a XML file.
     *
     * @param string $file Path to a file
     *
     * @return SimpleXMLElement
     *
     * @throws InvalidArgumentException When loading of XML file returns error
     */
    private function parseFile($file)
    {
        static $mappingSchema;

        if (!$mappingSchema) {
            $r = new \ReflectionClass('Rollerworks\Component\Search\Metadata\MetadataReaderInterface');
            $mappingSchema = dirname($r->getFileName()).'/schema/dic/metadata/metadata-1.0.xsd';
        }

        try {
            $dom = XmlUtils::loadFile($file, $mappingSchema);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidArgumentException(sprintf('Unable to parse file "%s".', $file), $e->getCode(), $e);
        }

        return simplexml_import_dom($dom, 'Rollerworks\\Component\\Search\\Metadata\\SimpleXMLElement');
    }
}
