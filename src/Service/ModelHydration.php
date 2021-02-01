<?php

namespace App\Service;

use Exception;
use ReflectionClass;
use ReflectionObject;
use Throwable;
use function is_object;
use function is_string;

/**
 * Class ModelHydration
 *
 * @package App\Services
 */
class ModelHydration
{
    /**
     * @param string $className
     *
     * @throws Exception
     * @return ReflectionClass
     */
    private static function initializeReflectionClass(string $className)
    {
        $class = new ReflectionClass($className);

        if (false == $class->isInstantiable()) {
            throw new Exception(' "%1$s" is not instantiable.', $className);
        }

        return $class;
    }

    /**
     * @param string|object $class
     * @param array         $data
     *
     * @return object|null
     * @throws Exception
     */
    public static function fromArray($class, $data = [])
    {
        if (is_string($class)) {
            $class = self::initializeReflectionClass($class);
            $model = $class->newInstance();

            if (!is_object($model)) {
                throw new Exception(' "%1$s" is not a class.', $class->getShortName());
            }

            foreach ($data as $property => $value) {
                $method = 'set' . ucfirst($property);
                if ($class->hasMethod($method)) {
                    $model->$method($value);
                }
            }

            return $model;
        }

        $sourceReflection = new ReflectionObject($class);

        foreach ($data as $property => $value) {
            $method = 'set' . ucfirst($property);
            if ($sourceReflection->hasMethod($method)) {
                $class->$method($value);
            }
        }

        return null;
    }

    /**
     * @param string|object $destination
     * @param object $sourceObject
     *
     * @return object
     * @throws Exception
     */
    public static function fromModel($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = self::initializeReflectionClass($destination)->newInstance();
        }

        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);

        $sourceProperties = $sourceReflection->getProperties();

        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($value === null) {
                continue;
            }
            if ($destinationReflection->hasProperty($name)) {
                $propertyDestination = $destinationReflection->getProperty($name);
                $propertyDestination->setAccessible(true);
                try {
                    $propertyDestination->setValue($destination, $value);
                } catch (Throwable $e) {
                    //do nothing...  yet ;)
                }
                //@todo use catch EngineException
            }
        }

        return $destination;
    }
}
