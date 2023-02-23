<?php

namespace Module\View\Request;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Takemo101\Egg\Http\Exception\HttpException;
use Throwable;

class ValidationErrorHttpException extends HttpException
{
    /**
     * constructor
     *
     * @param ConstraintViolationListInterface $violations
     * @param string|null $message
     * @param Throwable|null $previous
     * @param integer $code
     */
    public function __construct(
        public readonly ConstraintViolationListInterface $violations,
        ?string $message = null,
        Throwable $previous = null,
        int $code = 0,
    ) {
        parent::__construct(
            statusCode: 422,
            headers: [],
            message: $message ?? 'Validation error!',
            previous: $previous,
            code: $code,
        );
    }

    /**
     * バリデーションエラーメッセージを取得
     *
     * @return array<string,string[]>
     */
    public function toMessages(): array
    {
        $messages = [];

        foreach ($this->violations as $violation) {
            $messages[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $messages;
    }
}
