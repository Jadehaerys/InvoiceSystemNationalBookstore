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
        $customers = collect([
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
        ])->map(function (array $customer) {
            return Customer::updateOrCreate(
                ['name' => $customer['name']],
                $customer,
            );
        })->keyBy('name');

        User::updateOrCreate([
            'email' => 'cashier@venticbranch.test',
        ], [
            'customer_id' => null,
            'is_admin' => true,
            'name' => 'Ventic Branch Cashier',
            'password' => 'password',
        ]);

        User::updateOrCreate([
            'email' => 'jade@venticbranch.test',
        ], [
            'customer_id' => $customers['Jade Ventic']->id,
            'is_admin' => false,
            'name' => 'Jade Ventic',
            'password' => 'password',
        ]);

        foreach ([
            [
                'name' => 'Introduction to Programming',
                'price' => 549.00,
                'stock_quantity' => 12,
                'category' => 'Books',
                'description' => 'Starter programming textbook for first-year students.',
            ],
            [
                'name' => 'Discrete Mathematics Workbook',
                'price' => 315.00,
                'stock_quantity' => 10,
                'category' => 'Books',
                'description' => 'Practice workbook with drills and examples.',
            ],
            [
                'name' => 'Campus Ledger Notebook',
                'price' => 48.00,
                'stock_quantity' => 48,
                'category' => 'School Supplies',
                'description' => 'Receipt-inspired sample item matching the project brief.',
            ],
            [
                'name' => 'A4 Bond Paper Pack',
                'price' => 189.00,
                'stock_quantity' => 20,
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
