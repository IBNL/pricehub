<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;

use Rakit\Validation\Validator;


class BrandEntityRakitValidator implements ValidatorInterface
{

  public function validate(BaseEntity $baseEntity): void
  {
    $data = $this->convertEntityForArray($baseEntity);

    $validation = (new Validator())->validate($data, [
      'name' => 'required|min:2|max:255',
      'logo' => 'nullable|min:2|max:255',
    ]);


    if ($validation->fails()) {
      foreach ($validation->errors()->all() as $error) {
        $baseEntity->notification->addError([
          'context' => 'brandEntity',
          'message' => $error,
        ]);
      }
    }
  }

  private function convertEntityForArray(BaseEntity $baseEntity): array
  {
    return [
      'name' => $baseEntity->name,
      'logo' => $baseEntity->logo,
    ];
  }
}