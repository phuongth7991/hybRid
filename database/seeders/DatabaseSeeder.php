<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * @throws \Throwable
     */
    public function run(): void
    {
        DB::transaction(
            static function () {
                User
                    ::query()
                    ->create(
                        [
                            'name'     => 'admin',
                            'email'    => 'admin@gmail.com',
                            'password' => bcrypt('1'),
                            'address'  => 'Phu Tho',
                            'status'   => true,
                        ]
                    );
            }
        );

        Config::query()
              ->insert(
                  [
                      [
                          'key'   => 'language_default',
                          'value' => 'jp',
                      ],
                  ]
              );

    }
}
