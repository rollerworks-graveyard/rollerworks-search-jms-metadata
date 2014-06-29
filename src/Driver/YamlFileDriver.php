<?php

/**
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) 2012-2014 Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search\Metadata\Driver;

use Metadata\MergeableClassMetadata;
use Rollerworks\Component\Search\Exception\InvalidArgumentException;
use Rollerworks\Component\Search\Metadata\PropertyMetadata;
use Symfony\Component\Yaml\Yaml;

/**
 * YamlFileDriver.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
class YamlFileDriver extends AbstractFileDriver
{
    /**
     * {@inheritdoc}
     */
    protected function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $className = $class->name;
        $classMetadata = new MergeableClassMetadata($className);
        $data = Yaml::parse($file);

        foreach ($data as $propertyName => $property) {
            $classMetadata->addPropertyMetadata(
                $this->createPropertyMetadata($className, $propertyName, $property)
            );
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
        return 'yml';
    }

    private function createPropertyMetadata($className, $propertyName, array $property)
    {
        $this->assertArrayValueExists('name', $property, $className, $propertyName);
        $this->assertArrayValueExists('type', $property, $className, $propertyName);

        $propertyMetadata = new PropertyMetadata($className, $propertyName);
        $propertyMetadata->fieldName = $property['name'];
        $propertyMetadata->required = (isset($property['required']) ? $property['required'] : false);
        $propertyMetadata->type = $property['type'];

        if (isset($property['options'])) {
            $propertyMetadata->options = $property['options'];
        }

        return $propertyMetadata;
    }

    private function assertArrayValueExists($key, array $property, $className, $propertyName)
    {
        if (!isset($property[$key])) {
            throw new InvalidArgumentException(
                sprintf(
                    'No "%s" found in property metadata of class "%s" property "%s".',
                    $key,
                    $className,
                    $propertyName
                )
            );
        }
    }
}
