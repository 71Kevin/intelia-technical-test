<?php

declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class SignUpStep3Request extends SignUpRequest
{
    public function __construct(Request $request)
    {
        parent::__construct($request, ['phone', 'mobile']);
    }

    public function getPhone(): ?string
    {
        return $this->getField('phone');
    }

    public function getMobile(): ?string
    {
        return $this->getField('mobile');
    }
}
