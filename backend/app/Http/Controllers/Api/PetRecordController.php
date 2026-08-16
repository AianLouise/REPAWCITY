<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PetRecordController extends Controller
{
    /**
     * Public care history for a pet — only non-sensitive record types.
     */
    public function index(Request $request, Pet $pet): JsonResponse
    {
        $records = $pet->records()
            ->whereIn('type', PetRecord::PUBLIC_TYPES)
            ->orderByDesc('record_date')
            ->get()
            ->map(fn ($r) => $this->publicShape($r));

        return response()->json(['data' => $records]);
    }

    /**
     * Admin: full care history for a pet (all record types).
     */
    public function adminIndex(Request $request, Pet $pet): JsonResponse
    {
        $records = $pet->records()
            ->with('creator')
            ->orderByDesc('record_date')
            ->get()
            ->map(fn ($r) => $this->adminShape($r));

        return response()->json(['data' => $records]);
    }

    /**
     * Admin: add a care record.
     */
    public function store(Request $request, Pet $pet): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:'.implode(',', PetRecord::TYPES)],
            'title' => ['required', 'string', 'max:250'],
            'details' => ['required', 'string'],
            'record_date' => ['required', 'date'],
        ]);

        $record = $pet->records()->create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Care record added.',
            'record' => $this->adminShape($record),
        ], 201);
    }

    /**
     * Admin: delete a care record.
     */
    public function destroy(Request $request, Pet $pet, PetRecord $record): JsonResponse
    {
        abort_if($record->pet_id !== $pet->id, 404);

        $record->delete();

        return response()->json(['message' => 'Care record deleted.']);
    }

    /**
     * @return array<string, mixed>
     */
    protected function publicShape(PetRecord $record): array
    {
        return [
            'id' => $record->id,
            'type' => $record->type,
            'title' => $record->title,
            'details' => $record->details,
            'record_date' => $record->record_date?->toDateString(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function adminShape(PetRecord $record): array
    {
        return [
            'id' => $record->id,
            'type' => $record->type,
            'title' => $record->title,
            'details' => $record->details,
            'record_date' => $record->record_date?->toDateString(),
            'created_by' => $record->creator?->fname.' '.$record->creator?->lname,
        ];
    }
}
