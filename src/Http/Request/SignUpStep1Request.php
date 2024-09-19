<?php

declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class SignUpStep1Request extends SignUpRequest
{
    public function __construct(Request $request)
    {
        parent::__construct($request, ['fullname', 'birthdate']);
    }

    public function getFullname(): ?string
    {
        return $this->getField('fullname');
    }

    public function getBirthdate(): ?string
    {
        return $this->getField('birthdate');
    }
}
