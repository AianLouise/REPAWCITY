<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Record a donation pledge (public, no payment gateway yet).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['required', 'string', 'email', 'max:255'],
            'type' => ['required', 'in:cash,in_kind'],
            'amount' => ['nullable', 'numeric', 'min:0', 'max:1000000', 'required_if:type,cash'],
            'item_description' => ['nullable', 'string', 'max:1000', 'required_if:type,in_kind'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $donation = Donation::create([
            ...$validated,
            'user_id' => $request->user()?->id,
        ]);

        return response()->json([
            'message' => 'Thank you for your generous donation!',
            'donation' => $donation,
        ], 201);
    }

    /**
     * Admin: list donations with cash total + in-kind count.
     */
    public function index(Request $request): JsonResponse
    {
        $donations = Donation::query()
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->latest('date')
            ->get();

        $cashTotal = Donation::where('type', 'cash')->sum('amount');
        $inKindCount = Donation::where('type', 'in_kind')->count();

        return response()->json([
            'totals' => [
                'cash' => number_format((float) $cashTotal, 2),
                'in_kind_count' => $inKindCount,
            ],
            'data' => $donations,
        ]);
    }
}
