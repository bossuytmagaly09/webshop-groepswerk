<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CheckoutForm extends Form
{
    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:100')]
    public string $firstName = '';

    #[Validate('required|string|max:100')]
    public string $lastName = '';

    #[Validate('required|string|max:255')]
    public string $addressLine1 = '';

    #[Validate('nullable|string|max:255')]
    public ?string $addressLine2 = null;

    #[Validate('required|string|max:20')]
    public string $postcode = '';

    #[Validate('required|string|max:100')]
    public string $city = '';

    #[Validate('required|string|max:100')]
    public string $country = '';

    #[Validate('nullable|string|max:30')]
    public ?string $phone = null;
}
