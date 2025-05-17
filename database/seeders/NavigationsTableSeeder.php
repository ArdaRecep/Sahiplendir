<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavigationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('navigations')->insert([
            [
                'id' => 1,
                'name' => 'Navigation Menu',
                'handle' => 'navigation-menu-tr',
                'language_id' => 1,
                'items' => json_encode([
                    "578b9e41-899f-48bc-a487-d923d8953482" => [
                        "label" => "Anasayfa",
                        "type" => "external-link",
                        "data" => [
                            "url" => "/tr",
                            "target" => null
                        ],
                        "children" => [
                            "3210b844-d1b3-473e-bd5c-e8b6e7bbd705" => [
                                "label" => "Anasayfa1",
                                "type" => "external-link",
                                "data" => [
                                    "url" => "/tr/anasayfa",
                                    "target" => null
                                ],
                                "children" => []
                            ]
                        ]
                    ]
                ]),
                'created_at' => '2024-10-22 14:58:43',
                'updated_at' => '2024-11-20 12:19:57',
            ],
            [
                'id' => 2,
                'name' => 'Footer Menu',
                'handle' => 'footer-menu-tr',
                'language_id' => 1,
                'items' => json_encode([
                    "04943994-ee5c-4272-9bf4-dbe584c6c443" => [
                        "label" => "Anasayfa",
                        "type" => "external-link",
                        "data" => [
                            "url" => "/tr",
                            "target" => null
                        ],
                        "children" => []
                    ]
                ]),
                'created_at' => '2024-11-01 17:08:51',
                'updated_at' => '2024-11-20 11:41:28',
            ],
            [
                'id' => 3,
                'name' => 'Popular Articles',
                'handle' => 'popular-articles-tr',
                'language_id' => 1,
                'items' => json_encode([
                    "176e4e6f-e332-4376-b539-f8d861bcb5fb" => [
                        "label" => "Blog",
                        "type" => "external-link",
                        "data" => [
                            "url" => "/tr/blog",
                            "target" => null
                        ],
                        "children" => []
                    ]
                ]),
                'created_at' => '2024-11-01 17:30:58',
                'updated_at' => '2024-11-20 11:42:25',
            ],
        ]);
    }
}
