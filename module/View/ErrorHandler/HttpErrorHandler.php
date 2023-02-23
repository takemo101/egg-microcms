<?php

namespace Module\View\ErrorHandler;

use Module\View\Request\ValidationErrorHttpException;
use Module\View\Session\FlashErrorMessages;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Takemo101\Egg\Http\ErrorHandler\HttpErrorHandler as ErrorHandler;
use Takemo101\Egg\Http\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

final class HttpErrorHandler extends ErrorHandler
{
    /**
     * HttpExceptionをハンドリングする
     *
     * @param Request $request
     * @param HttpException $error
     * @return Response
     */
    protected function handleHttpException(Request $request, HttpException $error): Response
    {
        // バリデーションエラーレスポンス
        if ($error instanceof ValidationErrorHttpException) {
            return $this->validationErrorResponse($request, $error);
        }

        return new Response(
            latte(
                'error.error',
                [
                    'error' => $error,
                ],
            ),
            $error->getStatusCode(),
        );
    }

    /**
     * バリデーションエラーレスポンス
     *
     * @param Request $request
     * @param ValidationErrorHttpException $error
     * @return Response
     */
    private function validationErrorResponse(
        Request $request,
        ValidationErrorHttpException $error
    ): Response {
        /** @var FlashErrorMessages */
        $errors = $this->container->make(FlashErrorMessages::class);
        $errors->put($error->toMessages());

        return new RedirectResponse(
            $request->headers->get('referer'),
        );
    }
}
