<?php

use Illuminate\Database\Seeder;
use App\Models\StaffTransport;

class StaffTransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $transports = [['name' =>'Walker'],['name' =>'Own Transport']];
      foreach ($transports as $item) {
        $transport = new StaffTransport;
        $transport->name = $item['name'];
        $transport->save();
      }
    }
}
