<?php

namespace Core\Domain\Factory;

use Core\Domain\Validation\DailyExtractionEntityRakitValidator;
use Core\Domain\Validation\ValidatorInterface;

class DailyExtractionEntityValidatorFactory
{
    public static function create(): ValidatorInterface
    {
        return new DailyExtractionEntityRakitValidator();
    }
}
