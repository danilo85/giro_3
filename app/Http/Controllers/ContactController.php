<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'subject.required' => 'O assunto é obrigatório.',
            'message.required' => 'A mensagem é obrigatória.',
            'message.max' => 'A mensagem não pode ter mais de 2000 caracteres.'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Por favor, corrija os erros abaixo.');
        }

        try {
            // Get contact email from settings, fallback to site owner email
            $contactEmail = Setting::get('contact_email');
            
            if (!$contactEmail) {
                // Fallback to site owner (user with ID 1)
                $siteOwner = User::find(1);
                
                if (!$siteOwner) {
                    return back()
                        ->withInput()
                        ->with('error', 'Erro interno: email de contato não configurado.');
                }
                
                $contactEmail = $siteOwner->email;
            }

            // Prepare email data
            $emailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'sent_at' => now()->format('d/m/Y H:i:s')
            ];

            // Send email to configured contact email
            Mail::to($contactEmail)->send(new ContactFormMail($emailData));

            return back()
                ->with('success', 'Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.');

        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao enviar sua mensagem. Tente novamente mais tarde.');
        }
    }

    /**
     * Show contact page
     */
    public function show()
    {
        return view('contact');
    }
}