<?php

namespace App\Livewire\Public;

use App\Models\ContactMessage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.shop')]
#[Title('Contact')]
class Contact extends Component
{
    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    protected array $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string',
        'message' => 'required|string|min:10',
    ];

    public function submit()
    {
        $this->validate();

        ContactMessage::create([
            'name' => $this->first_name.' '.$this->last_name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset(['first_name', 'last_name', 'email', 'subject', 'message']);

        $this->dispatch('message-sent');
    }

    public function render()
    {
        return view('livewire.public.contact');
    }
}
