<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    //

    public function sendContact(Request $request)
    {
        // return response()->json(['message' => $request->all()]);


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Gửi mail
        Mail::send('email.contact', [
            'name' => $request->name,
            'email' => $request->email,
            'bodyMessage' => $request->message,
            'phone' => $request->phone
        ], function ($message) {
            $message->to(env('CONTACT_RECEIVER_EMAIL'), 'Admin') // ✅ lấy từ .env
                ->subject('Liên hệ từ website');
        });
        return response()->json(['message' => 'Gửi thành công!']);
    }
}
