<?php

declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class SignUpStep3Request
{
    private $phone;
    private $mobile;

    public function __construct(Request $request)
    {
        $this->phone = $request->get('phone');
        $this->mobile = $request->get('mobile');
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }
}
