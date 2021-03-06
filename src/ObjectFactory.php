<?php

namespace Nijens\Utilities;

use ReflectionClass;
use ReflectionMethod;

/**
 * ObjectFactory
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities
 **/
class ObjectFactory extends AbstractSingleton
{
    /**
     * newInstance
     *
     * Returns an instance of $className with $arguments.
     *
     * $arguments is an array with key-value pairs for constructor arguments.
     * A key should be equal to the name of a parameter.
     *
     * @access public
     * @param  string $className
     * @param  array  $constructorArguments An array with key-value pairs for constructor arguments
     * @return mixed
     **/
    public function newInstance($className, array $constructorArguments = array())
    {
        if (class_exists($className)) {
            $class = new ReflectionClass($className);

            return $class->newInstanceArgs($this->getArgumentsFromConstructorArguments($class, $constructorArguments));
        }
    }

    /**
     * getArgumentsFromConstructorArguments
     *
     * Returns an array with arguments from $constructorArguments for creating a new instance of $class
     *
     * @access protected
     * @param  ReflectionClass $class
     * @param  array           $constructorArguments
     * @return array
     **/
    protected function getArgumentsFromConstructorArguments(ReflectionClass $class, array $constructorArguments)
    {
        $arguments = array();
        if (($constructorMethod = $class->getConstructor()) instanceof ReflectionMethod) {
            foreach ($constructorMethod->getParameters() as $parameter) {
                if (array_key_exists($parameter->getName(), $constructorArguments)) {
                    $arguments[] = $constructorArguments[$parameter->getName() ];
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                } else {
                    break;
                }
            }
        }

        return $arguments;
    }
}
