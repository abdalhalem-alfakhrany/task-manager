<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, $request) {
            logger($e);
            return new JsonResponse([
                'success' => false,
                'message' => 'unauthenticated access',
            ], Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->render(function (UnauthorizedException|AccessDeniedHttpException $e, $request) {
            logger($e);
            return new JsonResponse([
                'success' => false,
                'message' => 'unauthorized access',
            ], Response::HTTP_FORBIDDEN);
        });
    })->create();
