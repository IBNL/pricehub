<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;

use Rakit\Validation\Validator;


class DailyExtractionEntityRakitValidator implements ValidatorInterface
{

  public function validate(BaseEntity $baseEntity): void
  {
    $data = $this->convertEntityForArray($baseEntity);
    $validation = (new Validator())->validate($data, [
      'extraction_id' => 'required',
      'reference_date' => 'required',
    ]);

    if ($validation->fails()) {
      foreach ($validation->errors()->all() as $error) {
        $baseEntity->notification->addError([
          'context' => 'DailyExtractionEntity',
          'message' => $error,
        ]);
      }
    }
  }

  private function convertEntityForArray(BaseEntity $baseEntity): array
  {
    return [
      'extraction_id' => $baseEntity->extraction_id,
      'reference_date' => $baseEntity->reference_date,
      //'extraction_success' => $baseEntity->extraction_success,
      //'send_to_process' => $baseEntity->send_to_process,
      //'input' => $baseEntity->input,
      //'output' => $baseEntity->output,
    ];
  }
}