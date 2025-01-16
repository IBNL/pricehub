<?php

namespace Core\Domain\Factory;

use Core\Domain\Validation\BrandEntityRakitValidator;
use Core\Domain\Validation\ValidatorInterface;

class BrandEntityValidatorFactory
{
    public static function create(): ValidatorInterface
    {
        return new BrandEntityRakitValidator();
    }
}
