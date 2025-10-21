<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiUrl;
    private $apiToken;
    private $adminPhoneNumber;

    public function __construct()
    {
        $this->apiUrl = env('WHATSAPP_API_URL', 'https://api.whatsapp.com/send');
        $this->apiToken = env('WHATSAPP_API_TOKEN');
        $this->adminPhoneNumber = env('ADMIN_WHATSAPP_NUMBER', '+6281234567890');
    }

    /**
     * Send booking notification to admin
     */
    public function sendBookingNotification(Booking $booking)
    {
        try {
            $message = $this->formatBookingMessage($booking);
            
            // For now, we'll use the WhatsApp Web URL method
            // In production, you might want to use a WhatsApp Business API
            $whatsappUrl = $this->generateWhatsAppUrl($this->adminPhoneNumber, $message);
            
            Log::info('WhatsApp notification would be sent to admin', [
                'booking_id' => $booking->id,
                'client_name' => $booking->client->name,
                'whatsapp_url' => $whatsappUrl
            ]);

            return [
                'success' => true,
                'message' => 'Notification prepared',
                'whatsapp_url' => $whatsappUrl
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id ?? null
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send notification'
            ];
        }
    }

    /**
     * Format booking details into WhatsApp message
     */
    private function formatBookingMessage(Booking $booking)
    {
        $service = $booking->service;
        $client = $booking->client;

        $message = "🎉 *BOOKING BARU - JJ EVENTS*\n\n";
        $message .= "📋 *Detail Booking:*\n";
        $message .= "• ID: #{$booking->id}\n";
        $message .= "• Layanan: {$service->name}\n";
        $message .= "• Kategori: " . ucfirst(str_replace('_', ' ', $service->category)) . "\n\n";
        
        $message .= "👤 *Informasi Client:*\n";
        $message .= "• Nama: {$client->name}\n";
        $message .= "• Email: {$client->email}\n";
        $message .= "• Telepon: {$client->phone}\n";
        if ($client->whatsapp) {
            $message .= "• WhatsApp: {$client->whatsapp}\n";
        }
        if ($client->address) {
            $message .= "• Alamat: {$client->address}\n";
        }
        $message .= "\n";

        $message .= "📅 *Detail Acara:*\n";
        $message .= "• Tanggal: " . $booking->event_date->format('d F Y') . "\n";
        if ($booking->event_time) {
            $message .= "• Waktu: " . $booking->event_time->format('H:i') . "\n";
        }
        $message .= "• Lokasi: {$booking->event_location}\n";
        if ($booking->guest_count) {
            $message .= "• Jumlah Tamu: {$booking->guest_count} orang\n";
        }
        if ($booking->budget) {
            $message .= "• Budget: Rp " . number_format($booking->budget, 0, ',', '.') . "\n";
        }
        $message .= "\n";

        if ($booking->event_details) {
            $message .= "📝 *Detail Acara:*\n";
            $message .= $booking->event_details . "\n\n";
        }

        if ($booking->notes) {
            $message .= "💬 *Catatan Tambahan:*\n";
            $message .= $booking->notes . "\n\n";
        }

        $message .= "⏰ *Waktu Booking:* " . $booking->created_at->format('d F Y, H:i') . "\n\n";
        $message .= "Silakan hubungi client segera untuk konfirmasi dan diskusi lebih lanjut! 📞";

        return $message;
    }

    /**
     * Generate WhatsApp URL for web/app
     */
    private function generateWhatsAppUrl($phoneNumber, $message)
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Convert to international format if needed
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (substr($cleanPhone, 0, 2) !== '62') {
            $cleanPhone = '62' . $cleanPhone;
        }

        return "https://wa.me/{$cleanPhone}?text=" . urlencode($message);
    }

    /**
     * Send confirmation message to client
     */
    public function sendClientConfirmation(Booking $booking)
    {
        try {
            $client = $booking->client;
            $whatsappNumber = $client->whatsapp ?: $client->phone;
            
            if (!$whatsappNumber) {
                return ['success' => false, 'message' => 'No WhatsApp number available'];
            }

            $message = $this->formatClientConfirmationMessage($booking);
            $whatsappUrl = $this->generateWhatsAppUrl($whatsappNumber, $message);
            
            Log::info('Client confirmation message prepared', [
                'booking_id' => $booking->id,
                'client_name' => $client->name,
                'whatsapp_url' => $whatsappUrl
            ]);

            return [
                'success' => true,
                'message' => 'Client confirmation prepared',
                'whatsapp_url' => $whatsappUrl
            ];

        } catch (\Exception $e) {
            Log::error('Failed to prepare client confirmation', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id ?? null
            ]);

            return [
                'success' => false,
                'message' => 'Failed to prepare confirmation'
            ];
        }
    }

    /**
     * Format client confirmation message
     */
    private function formatClientConfirmationMessage(Booking $booking)
    {
        $client = $booking->client;
        $service = $booking->service;

        $message = "🎉 Terima kasih *{$client->name}*!\n\n";
        $message .= "Booking request Anda telah kami terima dengan detail:\n\n";
        $message .= "📋 *Detail Booking:*\n";
        $message .= "• ID: #{$booking->id}\n";
        $message .= "• Layanan: {$service->name}\n";
        $message .= "• Tanggal: " . $booking->event_date->format('d F Y') . "\n";
        $message .= "• Lokasi: {$booking->event_location}\n\n";
        
        $message .= "📞 *Langkah Selanjutnya:*\n";
        $message .= "• Tim kami akan menghubungi Anda dalam 24 jam\n";
        $message .= "• Kami akan diskusikan detail acara dan memberikan penawaran\n";
        $message .= "• Setelah deal, kami akan mulai persiapan acara Anda\n\n";
        
        $message .= "Untuk pertanyaan urgent, hubungi:\n";
        $message .= "📱 {$this->adminPhoneNumber}\n\n";
        $message .= "*JJ Events* - Creating Magical Moments ✨";

        return $message;
    }
}