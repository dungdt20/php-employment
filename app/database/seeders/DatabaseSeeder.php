<?php

namespace Database\Seeders;

use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(5)->create();

        DB::table('employments')->insert([
            [ 'company_name' => 'Vinamilk', 'job_title' => 'Dev1', 'start_date' => '2020-02-20', 'end_date' => '2020-03-20', 'user_id' => 1 ],
            [ 'company_name' => 'Coccoc', 'job_title' => 'Dev2', 'start_date' => '2020-03-20', 'end_date' => '2021-02-20', 'user_id' => 2 ],
            [ 'company_name' => 'Coccoc', 'job_title' => 'Dev3', 'start_date' => '2021-04-20', 'end_date' => null, 'user_id' => 1 ],
            [ 'company_name' => 'Vinamilk', 'job_title' => 'Dev4', 'start_date' => '2021-05-20', 'end_date' => null, 'user_id' => 3 ],
        ]);
    }
}
