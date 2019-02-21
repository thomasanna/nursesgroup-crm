<?php

use Illuminate\Database\Seeder;
use App\Models\BranchContact;
class BranchContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
          'branchId'  =>1,
          'name'  =>'Jobi',
        ],[
          'branchId'  =>1,
          'name'  =>'Surabi',
        ],[
          'branchId'  =>1,
          'name'  =>'Valerie',
        ],[
          'branchId'  =>1,
          'name'  =>'Peter',
        ],[
          'branchId'  =>1,
          'name'  =>'Jacob',
        ],[
          'branchId'  =>1,
          'name'  =>'Antony',
        ]];

        foreach ($data as $item) {
          $band = new BranchContact;
          $band->branchId = $item['branchId'];
          $band->name = $item['name'];
          $band->save();
        }

    }
}
