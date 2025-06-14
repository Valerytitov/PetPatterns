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
            ['prop_key' => '@ДС', 'prop_title' => 'Длина спинки', 'prop_hint' => 'Длина спинки питомца'],
            ['prop_key' => '@ДИ', 'prop_title' => 'Длина изделия', 'prop_hint' => 'Длина изделия по спинке'],
            ['prop_key' => '@ОГ', 'prop_title' => 'Обхват груди', 'prop_hint' => 'Обхват груди питомца'],
            ['prop_key' => '@ОТ', 'prop_title' => 'Обхват талии', 'prop_hint' => 'Обхват талии питомца'],
            ['prop_key' => '@ОШ', 'prop_title' => 'Обхват шеи', 'prop_hint' => 'Обхват шеи питомца'],
            ['prop_key' => '@Мпл', 'prop_title' => 'Расстояние между передними лапами', 'prop_hint' => 'Расстояние между передними лапами'],
            ['prop_key' => '@Дпл', 'prop_title' => 'Длина передних лап', 'prop_hint' => 'Длина передних лап'],
            ['prop_key' => '@Дзл', 'prop_title' => 'Длина задних лап', 'prop_hint' => 'Длина задних лап'],
        ];

        foreach ($props as $propData) {
            Prop::firstOrCreate(
                ['prop_key' => $propData['prop_key']],
                $propData
            );
        }
    }
}
