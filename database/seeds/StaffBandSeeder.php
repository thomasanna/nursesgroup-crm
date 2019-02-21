<?php

use Illuminate\Database\Seeder;
use App\Models\StaffBand;

class StaffBandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $bands = [
        ['name' =>'Band 1','price'=>10],
        ['name' =>'Band 2','price'=>10.5],
        ['name' =>'Band 3','price'=>11],
        ['name' =>'Band 4','price'=>11.5],
        ['name' =>'Band 5','price'=>12],
        ['name' =>'Band 6','price'=>12.5],
        ['name' =>'Band 7','price'=>13],
      ];
      foreach ($bands as $item) {
        $band = new StaffBand;
        $band->name = $item['name'];
        $band->save();
      }
    }
}
