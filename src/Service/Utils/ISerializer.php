<?php


namespace App\Service\Utils;


interface ISerializer
{
    public function serialize($object, string $format, ?array $groups);

    public function deserialize($data, string $class, string $format, ?array $groups);
}
