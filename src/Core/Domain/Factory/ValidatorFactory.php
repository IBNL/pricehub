<?php 

namespace Core\Domain\Factory;

use Core\Domain\Validation\ValidatorInterface;
use InvalidArgumentException;

class ValidatorFactory
{
    public static function create(string $validatorClass): ValidatorInterface
    {
        if (!class_exists($validatorClass)) {
            throw new InvalidArgumentException("Validator class {$validatorClass} does not exist.");
        }

        return new $validatorClass();
    }
}
