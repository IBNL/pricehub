<?php

namespace Tests\Feature\App\Http\Controller\Api\v1\Brand;

use App\Http\Controllers\Api\v1\Brand\BrandCreateBatchController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\CreateBatchBrandRequest;
use App\Models\BrandModel;
use App\Repositories\Eloquent\BrandEloquentRepository;
use Core\UseCase\Brand\CreateBatchBrandUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class BrandCreateBatchControllerTest extends TestCase
{
  protected BrandEloquentRepository $repository;
  protected Controller $controller;

  public function setUp(): void
  {
    $this->repository = new BrandEloquentRepository(
      model: new BrandModel()
    );

    $this->controller = new BrandCreateBatchController();

    parent::setUp();
  }

  public function test_create(): void
  {
    //arrange
    $createBatchBrandUseCase = new CreateBatchBrandUseCase(
      brandRepository: $this->repository
    );
    $brands = [
      ['name' => 'BrandName1', 'logo' => 'logo1.png'],
      ['name' => 'BrandName2', 'logo' => 'logo2.png'],
      ['name' => 'BrandName3', 'logo' => 'logo3.png'],
    ];

    $request = new CreateBatchBrandRequest();
    $request->headers->set('content-type', 'application/json');
    $request->setJson(new ParameterBag($brands));

    //action
    $response = $this->controller->__invoke(
      request: $request,
      createBatchBrandUseCase: $createBatchBrandUseCase
    );

    //assert
    $this->assertInstanceOf(JsonResponse::class, $response);
    $this->assertEquals(Response::HTTP_CREATED, $response->status());
  }

}
