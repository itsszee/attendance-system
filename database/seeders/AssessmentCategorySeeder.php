<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentCategory;

class AssessmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Disiplin Waktu',
                'description' => 'Ketepatan waktu saat jam masuk, istirahat, dan pulang kerja.',
                'type' => 'Karyawan',
                'is_active' => true,
            ],
            [
                'name' => 'Kerja Sama Tim',
                'description' => 'Kemampuan berkolaborasi dan berkomunikasi dengan rekan kerja.',
                'type' => 'Karyawan',
                'is_active' => true,
            ],
            [
                'name' => 'Tanggung Jawab',
                'description' => 'Penyelesaian tugas sesuai dengan tenggat waktu dan kualitas yang diharapkan.',
                'type' => 'Karyawan',
                'is_active' => true,
            ],
            [
                'name' => 'Inisiatif',
                'description' => 'Kemampuan memberikan ide atau solusi proaktif tanpa harus diminta.',
                'type' => 'Karyawan',
                'is_active' => true,
            ],
            [
                'name' => 'Kerapian & Penampilan',
                'description' => 'Kesesuaian pakaian kerja dengan standar perusahaan.',
                'type' => 'General',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            AssessmentCategory::firstOrCreate(
                ['name' => $category['name']], 
                $category
            );
        }
    }
}
