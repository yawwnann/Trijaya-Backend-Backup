<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use Illuminate\Support\Facades\DB;
class WilayahSeeder extends Seeder
{
    public function run()
    {
        // Import Provinces
        if (file_exists(base_path('database/seeders/data/provinces.csv'))) {
            $provinces = array_map('str_getcsv', file(base_path('database/seeders/data/provinces.csv')));
            $header = ['id', 'name'];
            foreach ($provinces as $row) {
                if (count($row) !== count($header))
                    continue;
                $data = array_combine($header, $row);
                Province::updateOrCreate(['id' => $data['id']], ['name' => $data['name']]);
            }
        }
        // Import Regencies
        if (file_exists(base_path('database/seeders/data/regencies.csv'))) {
            $regencies = array_map('str_getcsv', file(base_path('database/seeders/data/regencies.csv')));
            $header = ['id', 'province_id', 'name'];
            foreach ($regencies as $row) {
                if (count($row) !== count($header))
                    continue;
                $data = array_combine($header, $row);
                Regency::updateOrCreate(['id' => $data['id']], [
                    'province_id' => $data['province_id'],
                    'name' => $data['name']
                ]);
            }
        }
        // Import Districts
        if (file_exists(base_path('database/seeders/data/districts.csv'))) {
            $districts = array_map('str_getcsv', file(base_path('database/seeders/data/districts.csv')));
            $header = ['id', 'regency_id', 'name'];
            foreach ($districts as $row) {
                if (count($row) !== count($header))
                    continue;
                $data = array_combine($header, $row);
                District::updateOrCreate(['id' => $data['id']], [
                    'regency_id' => $data['regency_id'],
                    'name' => $data['name']
                ]);
            }
        }
    }
}