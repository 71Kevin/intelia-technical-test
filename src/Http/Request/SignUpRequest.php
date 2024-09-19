<?php

declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

abstract class SignUpRequest
{
    protected array $requestData;

    public function __construct(Request $request, array $requiredFields)
    {
        $this->requestData = [];
        foreach ($requiredFields as $field) {
            $this->requestData[$field] = $request->get($field);
        }
    }

    protected function getField(string $field): ?string
    {
        return $this->requestData[$field] ?? null;
    }
}
