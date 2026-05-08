<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'cashier@campusbookhub.test',
        ], [
            'name' => 'Campus Cashier',
            'password' => 'password',
        ]);

        foreach ([
            [
                'name' => 'Jade Ventic',
                'address' => 'Liloan, Cebu',
                'contact_number' => '0917-000-1122',
            ],
            [
                'name' => 'Marco Dela Cruz',
                'address' => 'Mandaue City, Cebu',
                'contact_number' => '0918-555-0199',
            ],
        ] as $customer) {
            Customer::updateOrCreate(
                ['name' => $customer['name']],
                $customer,
            );
        }

        foreach ([
            [
                'name' => 'Introduction to Programming',
                'price' => 549.00,
                'category' => 'Books',
                'description' => 'Starter programming textbook for first-year students.',
            ],
            [
                'name' => 'Discrete Mathematics Workbook',
                'price' => 315.00,
                'category' => 'Books',
                'description' => 'Practice workbook with drills and examples.',
            ],
            [
                'name' => 'Campus Ledger Notebook',
                'price' => 48.00,
                'category' => 'School Supplies',
                'description' => 'Receipt-inspired sample item matching the project brief.',
            ],
            [
                'name' => 'A4 Bond Paper Pack',
                'price' => 189.00,
                'category' => 'School Supplies',
                'description' => 'Everyday printing paper pack for student use.',
            ],
        ] as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product,
            );
        }
    }
}
