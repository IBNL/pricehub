<?php

use Core\Domain\Exception\UnauthorizedException;
use Core\Domain\Notification\NotificationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    //
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (NotificationException $exception) {
      return response()->json(["message" => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
    });
    $exceptions->render(function (UnauthorizedException $exception) {
      return response()->json(["message" => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
    });
  })->create();
