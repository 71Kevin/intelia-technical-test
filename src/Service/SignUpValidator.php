<?php

declare(strict_types=1);

namespace App\Service;

use App\Http\Request\SignUpRequest;
use App\Repository\UserRepository;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpValidator
{
    private const ERROR_MESSAGE_UNIQUE = 'User with such %s already registered';

    private UserRepository $userRepository;
    private ValidatorInterface $validator;
    private array $errors = [];

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function validate(SignUpRequest $signUpRequest): bool
    {
        $violations = $this->validator->validate($signUpRequest);
        if ($violations->count() > 0) {
            $this->errors = $this->convertViolations($violations);
            return false;
        }

        $this->checkUniqueFields($signUpRequest);

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function convertViolations(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }

    private function checkUniqueFields(SignUpRequest $signUpRequest): void
    {
        $uniqueFields = [
            'email' => $signUpRequest->getEmail(),
            'username' => $signUpRequest->getUsername(),
        ];

        foreach ($uniqueFields as $field => $value) {
            if ($this->isFieldTaken($field, $value)) {
                $this->errors[$field] = sprintf(self::ERROR_MESSAGE_UNIQUE, $field);
            }
        }
    }

    private function isFieldTaken(string $field, string $value): bool
    {
        return count($this->userRepository->findBy([$field => $value])) > 0;
    }
}
