<?php

use Illuminate\Database\Seeder;
use App\Models\StaffBand;

class StaffBandSeederWithPrice extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $bands = [
        ['name' =>'Band 1','price'=>9],
        ['name' =>'Band 2','price'=>9.5],
        ['name' =>'Band 3','price'=>10],
        ['name' =>'Band 4','price'=>10.5],
        ['name' =>'Band 5','price'=>11],
        ['name' =>'Band 6','price'=>11.5],
        ['name' =>'Band 7','price'=>12],
        ['name' =>'Band 8','price'=>12.5],
        ['name' =>'Band 9','price'=>13],
        ['name' =>'Band 10','price'=>13.5],
        ['name' =>'Band 20','price'=>19],
        ['name' =>'Band 21','price'=>19.5],
        ['name' =>'Band 22','price'=>20],
        ['name' =>'Band 23','price'=>20.5],
        ['name' =>'Band 24','price'=>21],
        ['name' =>'Band 25','price'=>21.5],
        ['name' =>'Band 26','price'=>22],
        ['name' =>'Band 27','price'=>22.5],
        ['name' =>'Band 28','price'=>23],
        ['name' =>'Band 29','price'=>23.5],
        ['name' =>'Band 30','price'=>24]
      ];
      foreach ($bands as $item) {
        $band = new StaffBand;
        $band->name = $item['name'];
        $band->price = $item['price'];
        $band->save();
      }
    }
}
