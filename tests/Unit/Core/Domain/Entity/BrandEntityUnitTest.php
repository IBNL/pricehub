<?php

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\BrandEntity;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\ValueObjectUuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class BrandEntityUnitTest extends TestCase
{

  public function testAttributes(): void
  {

    $uuid = ValueObjectUuid::create();
    $createdAt = new DateTime();

    $brandEntity = new BrandEntity(
      id: new ValueObjectUuid($uuid),
      name: 'new brand',
      logo: 'logo_url',
      created_at: $createdAt
    );

    $this->assertEquals($uuid, $brandEntity->id());
    $this->assertEquals('new brand', $brandEntity->name);
    $this->assertEquals('logo_url', $brandEntity->logo);
    $this->assertEquals($createdAt->format('Y-m-d H:i:s'), $brandEntity->createdAt());
  }

  #[DataProvider('dataProviderValidator')]
  public function testValidator(
    string $name,
    ?string $logo = ''
  ): void {
    $this->expectException(NotificationException::class);

    new BrandEntity(
      name: $name,
      logo: $logo
    );
  }

  public static function dataProviderValidator(): array
  {
    return [
      'invalid name and empty logo' => [
        'a',
        ''
      ],
      'invalid name and valid logo' => [
        'a',
        'logo_url'
      ],
      'valid name and invalid logo' => [
        'name_ok',
        'a'
      ],
      'invalid name and invalid logo' => [
        'a',
        'a'
      ],
    ];
  }
}
