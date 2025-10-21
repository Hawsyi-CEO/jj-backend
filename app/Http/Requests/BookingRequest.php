<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Client data
            'client.name' => 'required|string|max:255',
            'client.email' => 'required|email|max:255',
            'client.phone' => 'required|string|max:20',
            'client.whatsapp' => 'nullable|string|max:20',
            'client.address' => 'nullable|string|max:500',
            
            // Booking data
            'service_id' => 'required|exists:services,id',
            'event_date' => 'required|date|after:today',
            'event_time' => 'nullable|date_format:H:i',
            'event_location' => 'required|string|max:255',
            'event_details' => 'nullable|string|max:1000',
            'guest_count' => 'nullable|integer|min:1',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the validation error messages.
     */
    public function messages(): array
    {
        return [
            'client.name.required' => 'Nama lengkap wajib diisi',
            'client.email.required' => 'Email wajib diisi',
            'client.email.email' => 'Format email tidak valid',
            'client.phone.required' => 'Nomor telepon wajib diisi',
            'service_id.required' => 'Layanan wajib dipilih',
            'service_id.exists' => 'Layanan yang dipilih tidak valid',
            'event_date.required' => 'Tanggal acara wajib diisi',
            'event_date.after' => 'Tanggal acara harus setelah hari ini',
            'event_location.required' => 'Lokasi acara wajib diisi',
            'guest_count.min' => 'Jumlah tamu minimal 1 orang',
            'budget.min' => 'Budget tidak boleh negatif',
        ];
    }
}
