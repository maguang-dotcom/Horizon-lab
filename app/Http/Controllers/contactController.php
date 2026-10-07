<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(ContactRequest $request)
    {
        $data = $request->validated();

        Mail::to(config('mail.contact_recipient', 'info@backupmedical.co.uk'))
            ->send(new ContactFormSubmitted($data));

        return back()->with('success', 'Thanks — your enquiry has been sent. We\'ll be in touch shortly.');
    }
}