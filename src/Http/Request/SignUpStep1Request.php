<?php

declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class SignUpStep1Request
{
    private $fullname;
    private $birthdate;

    public function __construct(Request $request)
    {
        $this->fullname = $request->get('fullname');
        $this->birthdate = $request->get('birthdate');
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }
}
