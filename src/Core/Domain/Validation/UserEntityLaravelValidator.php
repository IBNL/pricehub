<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


class UserEntityLaravelValidator implements ValidatorInterface
{

  public function validate(BaseEntity $baseEntity): void
  {
    $data = $this->convertEntityForArray($baseEntity);
    $validator = Validator::make($data, [
      'name' => 'required|min:3|max:255',
      'email' => 'required|email',
      'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->messages() as $error) {
        $baseEntity->notification->addError([
          'context' => 'UserEntity',
          'message' => $error[0],
        ]);
      }
    }

  }

  private function convertEntityForArray(BaseEntity $baseEntity): array
  {
    return [
      'name' => $baseEntity->name,
      'email' => $baseEntity->email,
      'password' => $baseEntity->password,
    ];
  }
}