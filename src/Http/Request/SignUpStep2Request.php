<?php

declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class SignUpStep2Request extends SignUpRequest
{
    public function __construct(Request $request)
    {
        parent::__construct($request, ['address']);
    }

    public function getAddress(): ?string
    {
        return $this->getField('address');
    }
}
