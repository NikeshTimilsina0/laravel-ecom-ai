<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::create(['role_name' => 'super_admin']);
        $vendorRole = Role::create(['role_name' => 'vendor']);
        $customerRole = Role::create(['role_name' => 'customer']);

        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
        ]);

        User::create([
            'name' => 'Vendor One',
            'email' => 'vendor1@example.com',
            'password' => Hash::make('password'),
            'role_id' => $vendorRole->id,
        ]);

        User::create([
            'name' => 'Vendor Two',
            'email' => 'vendor2@example.com',
            'password' => Hash::make('password'),
            'role_id' => $vendorRole->id,
        ]);

        User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role_id' => $customerRole->id,
        ]);

        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Clothing', 'description' => 'Fashion and apparel'],
            ['name' => 'Books', 'description' => 'Books and publications'],
            ['name' => 'Home & Garden', 'description' => 'Home improvement and gardening'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('  Super Admin: admin@example.com / password');
        $this->command->info('  Vendor: vendor1@example.com / password');
        $this->command->info('  Customer: customer@example.com / password');
    }
}
