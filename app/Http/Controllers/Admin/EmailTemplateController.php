<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmailTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    public function index(): View
    {
        $templates = EmailTemplate::query()->orderBy('id')->get();

        return view('admin.email-templates.index', [
            'templates' => $templates,
        ]);
    }

    public function edit(EmailTemplate $emailTemplate): View
    {
        return view('admin.email-templates.edit', [
            'template' => $emailTemplate,
        ]);
    }

    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate): RedirectResponse
    {
        $emailTemplate->update([
            'name' => $request->input('name'),
            'subject' => $request->input('subject'),
            'email_body' => $request->input('email_body'),
            'email_status' => (int) $request->input('email_status', 1),
        ]);

        return redirect()
            ->route('admin.email-templates.index')
            ->with('success', 'Email template updated successfully.');
    }
}
