<?php

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $shifts = [
        ['name' =>'Early','colorCode'=>'0ECFE3'],
        ['name' =>'Late','colorCode'=>'E3C60E'],
        ['name' =>'Longday','colorCode'=>'0EE31E'],
        ['name' =>'Night','colorCode'=>'E1AA0A'],
        ['name' =>'TWLight','colorCode'=>'960AE1'],
        ['name' =>'SLP Night','colorCode'=>'986C07'],
      ];
      foreach ($shifts as $item) {
        $shift = new Shift;
        $shift->name = $item['name'];
        $shift->colorCode = $item['colorCode'];
        $shift->save();
      }
    }
}
