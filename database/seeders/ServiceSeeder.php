<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // MC Services
        Service::create([
            'name' => 'MC Pernikahan Eksklusif',
            'description' => 'Layanan MC profesional untuk acara pernikahan dengan pengalaman lebih dari 5 tahun. Kami akan memandu acara pernikahan Anda dengan penuh kehangatan dan profesionalisme, menciptakan momen yang tak terlupakan.',
            'price' => 2500000,
            'price_range' => '2,000,000 - 3,500,000',
            'features' => [
                'Konsultasi pra-acara gratis',
                'Rundown acara detail',
                'MC berpengalaman 5+ tahun',
                'Koordinasi dengan vendor lain',
                'Backup MC standby',
                'Dokumentasi rundown lengkap'
            ],
            'duration' => '6-8 jam',
            'category' => 'mc',
            'is_active' => true
        ]);

        Service::create([
            'name' => 'MC Tunangan & Lamaran',
            'description' => 'Layanan MC untuk acara tunangan dan lamaran yang berkesan. Menciptakan momen romantis dan tak terlupakan untuk pasangan dengan sentuhan personal yang istimewa.',
            'price' => 1500000,
            'price_range' => '1,200,000 - 2,000,000',
            'features' => [
                'Konsultasi acara gratis',
                'Rundown khusus tunangan',
                'MC berpengalaman romantis',
                'Koordinasi momen surprise',
                'Dokumentasi momen spesial',
                'Background music romantis'
            ],
            'duration' => '3-4 jam',
            'category' => 'mc',
            'is_active' => true
        ]);

        Service::create([
            'name' => 'MC Acara Korporat',
            'description' => 'Layanan MC profesional untuk acara korporat, seminar, dan acara formal lainnya. Berpengalaman dalam menangani berbagai jenis acara bisnis dengan protokol yang tepat.',
            'price' => 2000000,
            'price_range' => '1,500,000 - 3,000,000',
            'features' => [
                'MC profesional berpengalaman',
                'Briefing teknis mendalam',
                'Koordinasi protokol resmi',
                'Penanganan tamu VIP',
                'Kemampuan bilingual',
                'Keterampilan presentasi formal'
            ],
            'duration' => '4-6 jam',
            'category' => 'mc',
            'is_active' => true
        ]);

        // Wedding Organizer Services
        Service::create([
            'name' => 'Full Wedding Planning',
            'description' => 'Paket lengkap perencanaan pernikahan dari A sampai Z. Tim berpengalaman akan menangani semua detail pernikahan impian Anda.',
            'price' => 15000000,
            'price_range' => '12,000,000 - 25,000,000',
            'features' => [
                'Konsultasi unlimited',
                'Venue hunting & booking',
                'Vendor management',
                'Dekorasi & styling',
                'Catering coordination',
                'Photography/videography',
                'Wedding day coordination',
                'Timeline management',
                'Guest management',
                'Emergency handling'
            ],
            'duration' => '3-6 bulan persiapan',
            'category' => 'wedding_organizer',
            'is_active' => true
        ]);

        Service::create([
            'name' => 'Wedding Day Coordination',
            'description' => 'Layanan koordinasi hari H pernikahan. Memastikan semua berjalan lancar sesuai rencana yang telah dibuat.',
            'price' => 5000000,
            'price_range' => '4,000,000 - 7,000,000',
            'features' => [
                'Meeting pra-wedding',
                'Timeline coordination',
                'Vendor coordination',
                'Guest management',
                'Emergency handling',
                'Setup supervision',
                'Real-time problem solving'
            ],
            'duration' => '12-14 jam (hari H)',
            'category' => 'wedding_organizer',
            'is_active' => true
        ]);

        Service::create([
            'name' => 'Partial Wedding Planning',
            'description' => 'Layanan perencanaan pernikahan parsial untuk pasangan yang sudah memiliki konsep tapi butuh bantuan eksekusi.',
            'price' => 8000000,
            'price_range' => '6,000,000 - 12,000,000',
            'features' => [
                'Konsultasi design',
                'Selected vendor sourcing',
                'Budget management',
                'Timeline planning',
                'Decoration setup',
                'Day-of coordination'
            ],
            'duration' => '1-3 bulan persiapan',
            'category' => 'wedding_organizer',
            'is_active' => true
        ]);
    }
}
