<?php

declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class SignUpStep2Request
{
    private $address;

    public function __construct(Request $request)
    {
        $this->address = $request->get('address');
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
}
