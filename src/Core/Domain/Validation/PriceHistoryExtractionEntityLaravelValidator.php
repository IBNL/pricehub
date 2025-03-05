<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;
use Illuminate\Support\Facades\Validator;


class PriceHistoryExtractionEntityLaravelValidator implements ValidatorInterface
{

  public function validate(BaseEntity $baseEntity): void
  {
    $data = $this->convertEntityForArray($baseEntity);
    $validator = Validator::make($data, [
      'extraction_id' => 'required',
      'product_id' => 'required',
      'reference_date' => 'required',
      'price' => 'required|numeric|min:1',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->messages() as $error) {
        $baseEntity->notification->addError([
          'context' => 'PriceHistoryExtractionEntity',
          'message' => $error[0],
        ]);
      }
    }

  }

  private function convertEntityForArray(BaseEntity $baseEntity): array
  {
    return [
      'extraction_id' => $baseEntity->extraction_id,
      'product_id' => $baseEntity->product_id,
      'reference_date' => $baseEntity->reference_date,
      'price' => $baseEntity->price,
    ];
  }
}