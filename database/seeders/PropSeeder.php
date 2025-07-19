<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prop;

class PropSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $props = [
            ['prop_key' => '@ДС', 'prop_title' => 'Длина спинки', 'prop_hint' => 'Длина спинки питомца', 'sort_order' => 1],
            ['prop_key' => '@ОГ', 'prop_title' => 'Обхват груди', 'prop_hint' => 'Обхват груди питомца', 'sort_order' => 2],
            ['prop_key' => '@ОТ', 'prop_title' => 'Обхват талии', 'prop_hint' => 'Обхват талии питомца', 'sort_order' => 3],
            ['prop_key' => '@ОШ', 'prop_title' => 'Обхват шеи', 'prop_hint' => 'Обхват шеи питомца', 'sort_order' => 4],
            ['prop_key' => '@Дпл', 'prop_title' => 'Длина передних лап', 'prop_hint' => 'Длина передних лап', 'sort_order' => 5],
            ['prop_key' => '@Дзл', 'prop_title' => 'Длина задних лап', 'prop_hint' => 'Длина задних лап', 'sort_order' => 6],
            ['prop_key' => '@Мпл', 'prop_title' => 'Расстояние между передними лапами', 'prop_hint' => 'Расстояние между передними лапами', 'sort_order' => 7],
        ];

        foreach ($props as $propData) {
            Prop::firstOrCreate(
                ['prop_key' => $propData['prop_key']],
                $propData
            );
        }
    }
}
