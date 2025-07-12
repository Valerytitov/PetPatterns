<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prop;

class PropsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $props = [
            [
                'prop_key' => '@ДС',
                'prop_title' => 'Длина спины',
                'prop_hint' => 'Измерьте от основания шеи до основания хвоста',
                'sort_order' => 1
            ],
            [
                'prop_key' => '@ОГ',
                'prop_title' => 'Обхват груди',
                'prop_hint' => 'Измерьте по самой широкой части груди',
                'sort_order' => 2
            ],
            [
                'prop_key' => '@ОТ',
                'prop_title' => 'Обхват талии',
                'prop_hint' => 'Измерьте в самом узком месте',
                'sort_order' => 3
            ],
            [
                'prop_key' => '@ОШ',
                'prop_title' => 'Обхват шеи',
                'prop_hint' => 'Измерьте у основания шеи',
                'sort_order' => 4
            ],
            [
                'prop_key' => '@Дпл',
                'prop_title' => 'Длина передней лапы',
                'prop_hint' => 'От подмышки до запястья',
                'sort_order' => 5
            ],
            [
                'prop_key' => '@Дзл',
                'prop_title' => 'Длина задней лапы',
                'prop_hint' => 'От паха до колена',
                'sort_order' => 6
            ]
        ];

        foreach ($props as $prop) {
            Prop::create($prop);
        }
    }
}
