<?php

/*
 * This file is part of the RollerworksSearch Component package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search\Metadata\Spec;

use Metadata\MergeableClassMetadata;

class MetadataMatcher
{
    public static function equalMetadata($subject, $metadata)
    {
        if (!$metadata instanceof MergeableClassMetadata) {
            return false;
        }

        $subject->reflection = null;
        $subject->createdAt = null;

        foreach ($subject->methodMetadata as $property) {
            $property->reflection = null;
        }

        $metadata->reflection = null;
        $metadata->createdAt = null;

        foreach ($metadata->methodMetadata as $property) {
            $property->reflection = null;
        }

        return $metadata == $subject;
    }
}
