<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAssignment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'kpi_score' => 0,
        ]);

        // Create Employees
        $employee = User::create([
            'name' => 'Maruf',
            'email' => 'maruf@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'kpi_score' => 0,
        ]);

        $employee2 = User::create([
            'name' => 'Zarif',
            'email' => 'zarif@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'kpi_score' => 0,
        ]);

        // Create Products
        $products = [];
        $productData = [
            ['name' => 'Laptop', 'sku' => 'ELEC-001', 'price' => 999.99, 'stock_quantity' => 50, 'description' => 'High-performance laptop'],
            ['name' => 'Wireless Mouse', 'sku' => 'ELEC-002', 'price' => 29.99, 'stock_quantity' => 200, 'description' => 'Ergonomic wireless mouse'],
            ['name' => 'Mechanical Keyboard', 'sku' => 'ELEC-003', 'price' => 79.99, 'stock_quantity' => 150, 'description' => 'RGB mechanical keyboard'],
            ['name' => 'Monitor 27"', 'sku' => 'ELEC-004', 'price' => 349.99, 'stock_quantity' => 75, 'description' => '4K IPS monitor'],
            ['name' => 'USB-C Hub', 'sku' => 'ELEC-005', 'price' => 49.99, 'stock_quantity' => 300, 'description' => '7-in-1 USB-C hub'],
            ['name' => 'Webcam HD', 'sku' => 'ELEC-006', 'price' => 69.99, 'stock_quantity' => 120, 'description' => '1080p HD webcam'],
            ['name' => 'Headphones', 'sku' => 'ELEC-007', 'price' => 149.99, 'stock_quantity' => 80, 'description' => 'Noise-cancelling headphones'],
            ['name' => 'Desk Lamp', 'sku' => 'FURN-001', 'price' => 34.99, 'stock_quantity' => 100, 'description' => 'LED desk lamp'],
        ];

        foreach ($productData as $data) {
            $products[] = Product::create($data);
        }

        // Create Customers
        $customers = [];
        $customerData = [
            ['name' => 'Badhon', 'email' => 'badhon@example.com', 'phone' => '555-0101', 'address' => '123 Main St'],
            ['name' => 'Zahid', 'email' => 'zahid@example.com', 'phone' => '555-0102', 'address' => '456 Oak Ave'],
            ['name' => 'Maaya', 'email' => 'maaya@example.com', 'phone' => '555-0103', 'address' => '789 Pine Rd'],
            ['name' => 'Rubel', 'email' => 'rubel@example.com', 'phone' => '555-0104', 'address' => '321 Elm St'],
            ['name' => 'Saiful', 'email' => 'saiful@example.com', 'phone' => '555-0105', 'address' => '654 Maple Dr'],
        ];

        foreach ($customerData as $data) {
            $customers[] = Customer::create($data);
        }

        // Create some sample sales
        // Sale 1 - Recent
        $sale1 = Sale::create([
            'invoice_no' => 'INV-' . now()->format('YmdHis') . '0001',
            'customer_id' => $customers[0]->id,
            'employee_id' => $employee->id,
            'total' => 1029.98,
        ]);

        SaleItem::create(['sale_id' => $sale1->id, 'product_id' => $products[0]->id, 'price' => 999.99, 'quantity' => 1, 'subtotal' => 999.99]);
        SaleItem::create(['sale_id' => $sale1->id, 'product_id' => $products[1]->id, 'price' => 29.99, 'quantity' => 1, 'subtotal' => 29.99]);

        $customers[0]->update(['last_purchase_at' => now()]);

        // Sale 2 - Recent
        $sale2 = Sale::create([
            'invoice_no' => 'INV-' . now()->format('YmdHis') . '0002',
            'customer_id' => $customers[1]->id,
            'employee_id' => $employee2->id,
            'total' => 429.98,
        ]);

        SaleItem::create(['sale_id' => $sale2->id, 'product_id' => $products[3]->id, 'price' => 349.99, 'quantity' => 1, 'subtotal' => 349.99]);
        SaleItem::create(['sale_id' => $sale2->id, 'product_id' => $products[4]->id, 'price' => 49.99, 'quantity' => 2, 'subtotal' => 99.98]);

        $customers[1]->update(['last_purchase_at' => now()->subDays(5)]);

        // Sale 3 - Old (for lost customer demo)
        $sale3 = Sale::create([
            'invoice_no' => 'INV-' . now()->format('YmdHis') . '0003',
            'customer_id' => $customers[2]->id,
            'employee_id' => $employee->id,
            'total' => 149.99,
        ]);

        SaleItem::create(['sale_id' => $sale3->id, 'product_id' => $products[6]->id, 'price' => 149.99, 'quantity' => 1, 'subtotal' => 149.99]);

        $customers[2]->update(['last_purchase_at' => now()->subDays(120)]);

        // Deduct stock for sold items
        $products[0]->decrement('stock_quantity', 1);
        $products[1]->decrement('stock_quantity', 1);
        $products[3]->decrement('stock_quantity', 1);
        $products[4]->decrement('stock_quantity', 2);
        $products[6]->decrement('stock_quantity', 1);

        // Create a customer assignment
        CustomerAssignment::create([
            'customer_id' => $customers[2]->id,
            'employee_id' => $employee->id,
            'assigned_at' => now()->subDays(10),
        ]);

        // Ensure settings exist
        Setting::setValue('lost_customer_days', '90');
    }
}
