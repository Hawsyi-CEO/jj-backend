<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Service;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $bookings = Booking::with(['client', 'service'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bookings'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'service_id' => 'required|exists:services,id',
                'event_date' => 'required|date|after:today',
                'event_time' => 'nullable|date_format:H:i',
                'event_location' => 'required|string|max:255',
                'event_details' => 'nullable|string',
                'guest_count' => 'nullable|integer|min:1',
                'budget' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Create booking
            $booking = Booking::create($validator->validated());

            // Load relationships
            $booking->load(['client', 'service']);

            // Send WhatsApp notification to admin
            $whatsappResult = $this->whatsappService->sendBookingNotification($booking);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking,
                'whatsapp_notification' => $whatsappResult
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $booking = Booking::with(['client', 'service'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|in:pending,confirmed,cancelled,completed',
                'admin_notes' => 'nullable|string',
                'event_date' => 'sometimes|date',
                'event_time' => 'nullable|date_format:H:i',
                'event_location' => 'sometimes|string|max:255',
                'event_details' => 'nullable|string',
                'guest_count' => 'nullable|integer|min:1',
                'budget' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $booking->update($validator->validated());
            $booking->load(['client', 'service']);

            // If status changed to confirmed, send client confirmation
            if ($request->has('status') && $request->status === 'confirmed') {
                $this->whatsappService->sendClientConfirmation($booking);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete booking'
            ], 500);
        }
    }

    /**
     * Get bookings by status
     */
    public function getByStatus(string $status): JsonResponse
    {
        try {
            $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
            
            if (!in_array($status, $validStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status'
                ], 400);
            }

            $bookings = Booking::with(['client', 'service'])
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching bookings by status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bookings'
            ], 500);
        }
    }
}
