<?php

use Illuminate\Database\Seeder;

use App\Models\StaffCategory;

class StaffCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $categories = [
        ['name' =>'RGN','colorCode'=>'a0070c'],
        ['name' =>'HCA','colorCode'=>'07a01c'],
        ['name' =>'SHCA','colorCode'=>'048e86'],
        ['name' =>'RMN','colorCode'=>'0f12f0'],
        ['name' =>'RGN-DIA','colorCode'=>'f3f20b'],
        ['name' =>'OTHER','colorCode'=>'f00f0f']
      ];
      foreach ($categories as $item) {
        $category = new StaffCategory;
        $category->name = $item['name'];
        $category->colorCode = $item['colorCode'];
        $category->save();
      }
    }
}
