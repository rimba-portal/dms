<?php

declare(strict_types=1);

namespace Rimba\Dms\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DmsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach ([
            ['code' => 'QMS', 'name' => 'Quality Management System'],
            ['code' => 'MANUAL', 'name' => 'Quality Manuals'],
            ['code' => 'PROC', 'name' => 'Procedures'],
            ['code' => 'SOP', 'name' => 'Standard Operating Procedures'],
            ['code' => 'WI', 'name' => 'Work Instructions'],
            ['code' => 'FORM', 'name' => 'Forms and Records'],
            ['code' => 'EXT', 'name' => 'External Documents'],
        ] as $category) {
            DB::table('document_categories')->updateOrInsert(
                ['code' => $category['code']],
                [...$category, 'created_at' => $now, 'updated_at' => $now],
            );
        }
    }
}
