<?php

namespace App\Service\Utils;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class Serializer implements ISerializer
{
    private $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $encoders = [
            new JsonEncoder()
        ];
        $normalizers = [
            new ObjectNormalizer($classMetadataFactory)
        ];
        $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);

        $this->setSerializer($serializer);
    }

    public function serialize($object, string $format, ?array $groups = ['groups' => 'default']): string
    {
        return $this->getSerializer()->serialize($object, $format, $groups);
    }

    public function deserialize($data, string $class, string $format, ?array $groups = ['groups' => 'default']): object
    {
        return $this->getSerializer()->deserialize($data, $class, $format, $groups);
    }

    /**
     * @return \Symfony\Component\Serializer\Serializer
     */
    public function getSerializer(): \Symfony\Component\Serializer\Serializer
    {
        return $this->serializer;
    }

    /**
     * @param \Symfony\Component\Serializer\Serializer $serializer
     * @return Serializer
     */
    public function setSerializer(\Symfony\Component\Serializer\Serializer $serializer): Serializer
    {
        $this->serializer = $serializer;
        return $this;
    }
}
