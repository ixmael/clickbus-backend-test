<?php

namespace App\Util\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DateTimeNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = array())
    {
        return $object->format(\DateTime::ISO8601);
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \Datetime;
    }
}
