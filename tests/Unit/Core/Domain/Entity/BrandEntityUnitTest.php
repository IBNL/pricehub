<?php

namespace Tests\Unit\Core\Domain\Entity;

use Core\Domain\Entity\BrandEntity;
use Core\Domain\Notification\NotificationException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Support\Str;

class BrandEntityUnitTest extends TestCase
{

  #[DataProvider('dataProviderCreate')]
  public function testCreate(string $name, ?string $logo = ''): void
  {
    $brandEntity = new BrandEntity(
      name: $name,
      logo: $logo,
    );

    $this->assertTrue(Str::isUuid($brandEntity->id()));
    $this->assertEquals($name, $brandEntity->name);
    $this->assertEquals($logo, $brandEntity->logo);
    $this->assertNotEmpty($brandEntity->createdAt());
  }

  public static function dataProviderCreate(): array
  {
    return [
      'valid name and filled logo' => [
        'name' => 'new brand',
        'logo' => 'logo_url'
      ],
      'valid name and logo empty' => [
        'name' => 'new brand',
        'logo' => ''
      ],
    ];
  }

  #[DataProvider('dataProviderValidator')]
  public function testValidator(string $name, ?string $logo = ''): void
  {
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
