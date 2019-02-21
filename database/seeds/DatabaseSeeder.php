<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(StaffCategorySeeder::class);
        $this->call(StaffBandSeeder::class);
        $this->call(StaffTransportSeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(StaffBandSeederWithPrice::class);
        $this->call(TransportNewRowSeeder::class);
    }
}
