<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Hero Section
            [
                'section' => 'hero',
                'key' => 'title',
                'value' => 'Wujudkan Momen Pernikahan Impian Bersama Partner Terpercaya Anda',
                'metadata' => null
            ],
            [
                'section' => 'hero',
                'key' => 'subtitle',
                'value' => 'Ahli MC Profesional & Wedding Organizer Terpercaya untuk Momen Spesial Anda',
                'metadata' => null
            ],
            [
                'section' => 'hero',
                'key' => 'description',
                'value' => 'Lebih dari 8 tahun pengalaman menghadirkan momen berkesan dengan sentuhan profesional dan personal',
                'metadata' => null
            ],
            [
                'section' => 'hero',
                'key' => 'cta_primary',
                'value' => 'Konsultasi Gratis Sekarang',
                'metadata' => null
            ],
            [
                'section' => 'hero',
                'key' => 'cta_secondary',
                'value' => 'Lihat Paket Layanan',
                'metadata' => null
            ],

            // Services Section
            [
                'section' => 'services',
                'key' => 'title',
                'value' => 'Layanan Eksklusif Kami',
                'metadata' => null
            ],
            [
                'section' => 'services',
                'key' => 'subtitle',
                'value' => 'Paket lengkap untuk setiap momen berharga Anda',
                'metadata' => null
            ],
            [
                'section' => 'services',
                'key' => 'service_1_name',
                'value' => 'MC Profesional',
                'metadata' => null
            ],
            [
                'section' => 'services',
                'key' => 'service_1_price',
                'value' => 'Rp 2.500.000',
                'metadata' => null
            ],
            [
                'section' => 'services',
                'key' => 'service_1_description',
                'value' => 'Master of Ceremony yang berpengalaman untuk membuat acara Anda berkesan',
                'metadata' => [
                    'features' => [
                        'Pengalaman 8+ tahun',
                        'Bahasa Indonesia & Jawa',
                        'Rundown terstruktur'
                    ]
                ]
            ],
            [
                'section' => 'services',
                'key' => 'service_2_name',
                'value' => 'Wedding Organizer',
                'metadata' => null
            ],
            [
                'section' => 'services',
                'key' => 'service_2_price',
                'value' => 'Rp 8.000.000',
                'metadata' => null
            ],
            [
                'section' => 'services',
                'key' => 'service_2_description',
                'value' => 'Perencanaan menyeluruh untuk pernikahan impian Anda',
                'metadata' => [
                    'features' => [
                        'Konsultasi unlimited',
                        'Vendor terpercaya',
                        'Timeline management'
                    ]
                ]
            ],
            [
                'section' => 'services',
                'key' => 'service_3_name',
                'value' => 'Paket Premium',
                'metadata' => null
            ],
            [
                'section' => 'services',
                'key' => 'service_3_price',
                'value' => 'Rp 12.000.000',
                'metadata' => null
            ],
            [
                'section' => 'services',
                'key' => 'service_3_description',
                'value' => 'Solusi lengkap MC + WO untuk pengalaman tanpa khawatir',
                'metadata' => [
                    'features' => [
                        'MC + Wedding Organizer',
                        'Dekorasi eksklusif',
                        'Full dokumentasi'
                    ]
                ]
            ],

            // Testimonials
            [
                'section' => 'testimonials',
                'key' => 'testimonial_1_name',
                'value' => 'Dinda & Rizki',
                'metadata' => null
            ],
            [
                'section' => 'testimonials',
                'key' => 'testimonial_1_text',
                'value' => 'Gila sih, JJ Events bikin hari pernikahan kita sempurna banget!',
                'metadata' => ['rating' => 5]
            ],
            [
                'section' => 'testimonials',
                'key' => 'testimonial_2_name',
                'value' => 'Ayu & Budi',
                'metadata' => null
            ],
            [
                'section' => 'testimonials',
                'key' => 'testimonial_2_text',
                'value' => 'Pokoknya top markotop! MC-nya paham banget budaya dan adat.',
                'metadata' => ['rating' => 5]
            ],
            [
                'section' => 'testimonials',
                'key' => 'testimonial_3_name',
                'value' => 'Sari & Eko',
                'metadata' => null
            ],
            [
                'section' => 'testimonials',
                'key' => 'testimonial_3_text',
                'value' => 'Worth it banget! Planning-nya detail, hari H eksekusinya ciamik.',
                'metadata' => ['rating' => 5]
            ],

            // Footer / Contact
            [
                'section' => 'footer',
                'key' => 'whatsapp',
                'value' => '6289516438703',
                'metadata' => null
            ],
            [
                'section' => 'footer',
                'key' => 'email',
                'value' => 'info@jjevents.com',
                'metadata' => null
            ],
            [
                'section' => 'footer',
                'key' => 'instagram',
                'value' => '@jjevents',
                'metadata' => null
            ],
            [
                'section' => 'footer',
                'key' => 'address',
                'value' => 'Jakarta, Indonesia',
                'metadata' => null
            ],
        ];

        foreach ($contents as $content) {
            \App\Models\LandingContent::updateOrCreate(
                [
                    'section' => $content['section'],
                    'key' => $content['key']
                ],
                [
                    'value' => $content['value'],
                    'metadata' => $content['metadata']
                ]
            );
        }
    }
}
