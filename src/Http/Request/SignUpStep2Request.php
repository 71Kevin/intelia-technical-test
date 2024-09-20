<?php

declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class SignUpStep2Request extends SignUpRequest
{
    public function __construct(Request $request)
    {
        parent::__construct($request, ['street', 'number', 'zipCode', 'city', 'state']);
    }

    public function getStreet(): ?string
    {
        return $this->getField('street');
    }

    public function getNumber(): ?string
    {
        return $this->getField('number');
    }

    public function getZipCode(): ?string
    {
        return $this->getField('zipCode');
    }

    public function getCity(): ?string
    {
        return $this->getField('city');
    }

    public function getState(): ?string
    {
        return $this->getField('state');
    }
}
