<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;
use Illuminate\Support\Facades\Validator;


class ProductEntityLaravelValidator implements ValidatorInterface
{

  public function validate(BaseEntity $baseEntity): void
  {
    $data = $this->convertEntityForArray($baseEntity);
    $validator = Validator::make($data, [
      'url' => 'required|min:3|max:255',
      'name' => 'required|max:255',
      #'slug' => 'required|max:255',
      'logo_from_ecommerce' => 'nullable|max:800',
      'logo' => 'nullable|max:255',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->messages() as $error) {
        $baseEntity->notification->addError([
          'context' => 'ProductEntity',
          'message' => $error[0],
        ]);
      }
    }

  }

  private function convertEntityForArray(BaseEntity $baseEntity): array
  {
    return [
      'url' => $baseEntity->url,
      'name' => $baseEntity->name,
      'slug' => $baseEntity->slug,
      'logo_from_ecommerce' => $baseEntity->logo_from_ecommerce,
      'logo' => $baseEntity->logo,
    ];
  }
}