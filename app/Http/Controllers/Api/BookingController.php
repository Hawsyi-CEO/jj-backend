<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create or update client
            $client = Client::updateOrCreate(
                ['email' => $request->input('client.email')],
                $request->input('client')
            );

            // Create booking
            $booking = Booking::create([
                'client_id' => $client->id,
                'service_id' => $request->service_id,
                'event_date' => $request->event_date,
                'event_time' => $request->event_time,
                'event_location' => $request->event_location,
                'event_details' => $request->event_details,
                'guest_count' => $request->guest_count,
                'budget' => $request->budget,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            // Load relationships
            $booking->load(['client', 'service']);

            DB::commit();

            // Send WhatsApp notification (implement later)
            $this->sendWhatsAppNotification($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat. Kami akan segera menghubungi Anda.',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat booking. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking): JsonResponse
    {
        $booking->load(['client', 'service']);

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * Check if a date is available for booking.
     */
    public function checkAvailability(\Illuminate\Http\Request $request): JsonResponse
    {
        $date = $request->input('date');
        $serviceId = $request->input('service_id');

        $existingBookings = Booking::where('event_date', $date)
            ->where('service_id', $serviceId)
            ->whereIn('status', ['confirmed', 'pending'])
            ->count();

        $isAvailable = $existingBookings === 0;

        return response()->json([
            'success' => true,
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Tanggal tersedia' : 'Tanggal sudah terboking'
        ]);
    }

    /**
     * Send WhatsApp notification to admin.
     */
    private function sendWhatsAppNotification(Booking $booking): void
    {
        try {
            $message = $this->formatWhatsAppMessage($booking);
            
            // For now, just log the message
            // TODO: Implement actual WhatsApp API integration
            Log::info('WhatsApp notification: ' . $message);
            
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Format WhatsApp message.
     */
    private function formatWhatsAppMessage(Booking $booking): string
    {
        return "🎉 *BOOKING BARU* 🎉\n\n" .
               "📝 *Detail Client:*\n" .
               "Nama: {$booking->client->name}\n" .
               "Email: {$booking->client->email}\n" .
               "Phone: {$booking->client->phone}\n" .
               "WhatsApp: {$booking->client->whatsapp}\n\n" .
               "🎪 *Detail Acara:*\n" .
               "Layanan: {$booking->service->name}\n" .
               "Tanggal: {$booking->event_date->format('d F Y')}\n" .
               "Waktu: " . ($booking->event_time ? $booking->event_time->format('H:i') : 'Belum ditentukan') . "\n" .
               "Lokasi: {$booking->event_location}\n" .
               "Jumlah Tamu: " . ($booking->guest_count ?? 'Belum ditentukan') . "\n" .
               "Budget: " . ($booking->budget ? 'Rp ' . number_format($booking->budget, 0, ',', '.') : 'Belum ditentukan') . "\n\n" .
               "💬 *Catatan:*\n" .
               ($booking->notes ?? 'Tidak ada catatan') . "\n\n" .
               "⏰ Booking ID: #{$booking->id}\n" .
               "📅 Dibuat: {$booking->created_at->format('d F Y H:i')}";
    }
}
