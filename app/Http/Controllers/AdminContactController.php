<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class AdminContactController extends Controller
{
    public function edit()
    {
        $contact = Contact::first();
        if (!$contact) {
            $contact = Contact::create([]);
        }
        return view('admin.contacts.edit', compact('contact'));
    }

    public function update(Request $request)
    {
        $contact = Contact::first();
        if (!$contact) {
            $contact = Contact::create([]);
        }
        $data = $request->only(['email', 'phone', 'telegram', 'vk', 'instagram']);
        $contact->update($data);
        return redirect()->back()->with('success', 'Контакты обновлены!');
    }
} 