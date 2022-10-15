<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Contact extends Component
{
    public $name;
    public $email;
    public $question;
    public $message;

    protected $rules = [
        'name' => [
            'required',
        ],
        'email' => [
            'required',
            'email',
        ],
        'question' => [
            'required'
        ],
    ];

    public function submitForm()
    {
        $this->message = '';
        $this->validate();

        $this->name = '';
        $this->email = '';
        $this->question = '';

        $this->message = 'Email successfully sent';
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
