<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoContentController extends Controller
{
    public function promos(): View
    {
        return view('admin.content.promos', ['promos' => Promo::latest()->paginate(10)]);
    }

    public function storePromo(Request $request): RedirectResponse
    {
        Promo::create([
            ...$request->validate([
                'title' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:50', 'unique:promos,code'],
                'description' => ['nullable', 'string'],
                'discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            ]),
            'created_by' => $request->user()->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Promo dibuat dan menunggu approval manager.');
    }

    public function faqs(): View
    {
        return view('admin.content.faqs', ['faqs' => Faq::latest()->paginate(10)]);
    }

    public function storeFaq(Request $request): RedirectResponse
    {
        Faq::create($request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]));

        return back()->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function deleteFaq(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    public function contacts(): View
    {
        return view('admin.content.contacts', ['messages' => ContactMessage::latest()->paginate(10)]);
    }
}
