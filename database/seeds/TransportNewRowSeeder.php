<?php

use Illuminate\Database\Seeder;
use App\Models\StaffTransport;

class TransportNewRowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $transports = ['Self Transport','Transport Required'];
      foreach ($transports as $item) {
        $transport = new StaffTransport;
        $transport->name = $item;
        $transport->save();
      }
    }
}
