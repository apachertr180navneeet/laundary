<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $company = Company::create([
            'name' => 'Default Company',
            'slug' => 'default-company',
            'email' => 'info@defaultcompany.com',
            'phone' => '8000000000',
            'address' => '123 Main Street',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'gst_number' => '07AABCU9603R1Z1',
            'status' => 1,
        ]);

        // Associate the existing admin user with this company
        User::where('email', 'projectadmin@mailinator.com')->update([
            'company_id' => $company->id,
        ]);
    }
}
