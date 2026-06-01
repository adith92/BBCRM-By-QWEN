<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Invoice;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Spatie Roles
        $roleGm = Role::firstOrCreate(['name' => 'gm']);
        $roleSales = Role::firstOrCreate(['name' => 'sales']);
        $roleFinance = Role::firstOrCreate(['name' => 'finance']);
        $roleOps = Role::firstOrCreate(['name' => 'ops']);

        // 1b. Create Spatie Permissions
        $pFleet = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'fleet.view']);
        $pBooking = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'booking.view']);
        $pFinance = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'finance.view']);
        $pCrm = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'crm.view']);

        // 1c. Sync Permissions to Roles
        $roleGm->syncPermissions([$pFleet, $pBooking, $pFinance, $pCrm]);
        $roleSales->syncPermissions([$pFleet]);
        $roleFinance->syncPermissions([$pFinance]);
        $roleOps->syncPermissions([$pBooking]);

        // 2. Create 4 Users with Roles (Idempotent)
        // Hapus user GM lama jika email tidak cocok (untuk mengatasi migrasi email)
        User::where('email', 'gm@golden-bird-crm.test')->delete();

        $gmUser = User::where('email', 'gm@goldenbird.com')->first();
        if (!$gmUser) {
            $gmUser = User::create([
                'name' => 'General Manager',
                'email' => 'gm@goldenbird.com',
                'password' => 'demo1234', // Casting 'hashed' di User model otomatis hash
                'phone' => '08111222333',
                'status' => 'active',
            ]);
            $gmUser->assignRole($roleGm);
        }

        $salesUser = User::where('email', 'sales@goldenbird.com')->first();
        if (!$salesUser) {
            $salesUser = User::create([
                'name' => 'Sales Officer',
                'email' => 'sales@goldenbird.com',
                'password' => 'demo1234', // Casting 'hashed' di User model otomatis hash
                'phone' => '08222333444',
                'status' => 'active',
            ]);
            $salesUser->assignRole($roleSales);
        }

        $financeUser = User::where('email', 'finance@goldenbird.com')->first();
        if (!$financeUser) {
            $financeUser = User::create([
                'name' => 'Finance Admin',
                'email' => 'finance@goldenbird.com',
                'password' => 'demo1234', // Casting 'hashed' di User model otomatis hash
                'phone' => '08333444555',
                'status' => 'active',
            ]);
            $financeUser->assignRole($roleFinance);
        }

        $opsUser = User::where('email', 'ops@goldenbird.com')->first();
        if (!$opsUser) {
            $opsUser = User::create([
                'name' => 'Operations Head',
                'email' => 'ops@goldenbird.com',
                'password' => 'demo1234', // Casting 'hashed' di User model otomatis hash
                'phone' => '08444555666',
                'status' => 'active',
            ]);
            $opsUser->assignRole($roleOps);
        }

        // 3. Create 5 Clients (Idempotent)
        $clientsData = [
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@pertamina.com', 'phone' => '08129876543', 'company' => 'Pertamina Persero', 'address' => 'Jl. Perwira No. 2, Jakarta Pusat'],
            ['name' => 'Jessica Tan', 'email' => 'jessica.tan@astra.co.id', 'phone' => '08112345678', 'company' => 'Astra International', 'address' => 'Jl. Gaya Motor Raya No. 8, Jakarta Utara'],
            ['name' => 'Aditya Wijaya', 'email' => 'aditya.wijaya@telkomsel.co.id', 'phone' => '08134567890', 'company' => 'Telkomsel', 'address' => 'Telkom Landmark Tower, Jakarta Selatan'],
            ['name' => 'Siti Aminah', 'email' => 'siti.aminah@unilever.com', 'phone' => '08156789012', 'company' => 'Unilever Indonesia', 'address' => 'BSD Green Office Park, Tangerang'],
            ['name' => 'David Miller', 'email' => 'david.miller@mcdonalds.co.id', 'phone' => '08178901234', 'company' => 'McDonalds Indonesia', 'address' => 'Jl. M.H. Thamrin No. 14, Jakarta Pusat'],
        ];

        $clients = [];
        foreach ($clientsData as $c) {
            $clients[] = Client::firstOrCreate(['email' => $c['email']], $c);
        }

        // 4. Create 10 Vehicles (Idempotent)
        $vehiclesData = [
            ['plate' => 'B 1001 SBA', 'brand' => 'Toyota', 'model' => 'Alphard 2.5G', 'status' => 'available'],
            ['plate' => 'B 2002 SBB', 'brand' => 'Toyota', 'model' => 'Innova Zenix Hybrid', 'status' => 'available'],
            ['plate' => 'B 3003 SBC', 'brand' => 'Toyota', 'model' => 'Camry 2.5V', 'status' => 'available'],
            ['plate' => 'B 4004 SBD', 'brand' => 'Mercedes-Benz', 'model' => 'E300 AMG Line', 'status' => 'po'],
            ['plate' => 'B 5005 SBE', 'brand' => 'BMW', 'model' => '520i M Sport', 'status' => 'po'],
            ['plate' => 'B 6006 SBF', 'brand' => 'Hyundai', 'model' => 'Ioniq 5 Signature', 'status' => 'maintenance'],
            ['plate' => 'D 7007 ABC', 'brand' => 'Toyota', 'model' => 'Innova Reborn', 'status' => 'maintenance'],
            ['plate' => 'D 8008 XYZ', 'brand' => 'Toyota', 'model' => 'HiAce Premio', 'status' => 'available'],
            ['plate' => 'L 9009 ZZZ', 'brand' => 'Honda', 'model' => 'Accord 2.0 Hybrid', 'status' => 'hold'],
            ['plate' => 'B 1111 GBC', 'brand' => 'Lexus', 'model' => 'LM 350', 'status' => 'hold'],
        ];

        $vehicles = [];
        foreach ($vehiclesData as $v) {
            $vehicles[] = Vehicle::firstOrCreate(['plate' => $v['plate']], $v);
        }

        // 5. Create 5 Bookings (Idempotent)
        if (Booking::count() === 0) {
            $bookingsData = [
                [
                    'client_id' => $clients[0]->id,
                    'vehicle_id' => $vehicles[3]->id, // Mercedes-Benz (po)
                    'start_datetime' => Carbon::now()->addDays(1)->setTime(9, 0, 0),
                    'end_datetime' => Carbon::now()->addDays(3)->setTime(17, 0, 0),
                    'status' => 'confirmed',
                ],
                [
                    'client_id' => $clients[1]->id,
                    'vehicle_id' => $vehicles[4]->id, // BMW (po)
                    'start_datetime' => Carbon::now()->addDays(2)->setTime(8, 0, 0),
                    'end_datetime' => Carbon::now()->addDays(5)->setTime(18, 0, 0),
                    'status' => 'confirmed',
                ],
                [
                    'client_id' => $clients[2]->id,
                    'vehicle_id' => $vehicles[1]->id, // Innova Zenix
                    'start_datetime' => Carbon::now()->subDays(5)->setTime(10, 0, 0),
                    'end_datetime' => Carbon::now()->subDays(3)->setTime(16, 0, 0),
                    'status' => 'completed',
                ],
                [
                    'client_id' => $clients[3]->id,
                    'vehicle_id' => $vehicles[0]->id, // Alphard
                    'start_datetime' => Carbon::now()->addDays(7)->setTime(7, 0, 0),
                    'end_datetime' => Carbon::now()->addDays(8)->setTime(21, 0, 0),
                    'status' => 'pending',
                ],
                [
                    'client_id' => $clients[4]->id,
                    'vehicle_id' => $vehicles[2]->id, // Camry
                    'start_datetime' => Carbon::now()->subDays(10)->setTime(9, 0, 0),
                    'end_datetime' => Carbon::now()->subDays(9)->setTime(18, 0, 0),
                    'status' => 'cancelled',
                ],
            ];

            $bookings = [];
            foreach ($bookingsData as $b) {
                $bookings[] = Booking::create($b);
            }

            // 6. Create 5 Invoices matching the Bookings (Idempotent)
            $invoicesData = [
                [
                    'booking_id' => $bookings[0]->id,
                    'total_amount' => 6000000.00,
                    'remaining_balance' => 0.00,
                    'status' => 'paid',
                ],
                [
                    'booking_id' => $bookings[1]->id,
                    'total_amount' => 7500000.00,
                    'remaining_balance' => 2500000.00,
                    'status' => 'partially_paid',
                ],
                [
                    'booking_id' => $bookings[2]->id,
                    'total_amount' => 2400000.00,
                    'remaining_balance' => 0.00,
                    'status' => 'paid',
                ],
                [
                    'booking_id' => $bookings[3]->id,
                    'total_amount' => 3500000.00,
                    'remaining_balance' => 3500000.00,
                    'status' => 'unpaid',
                ],
                [
                    'booking_id' => $bookings[4]->id,
                    'total_amount' => 1500000.00,
                    'remaining_balance' => 1500000.00,
                    'status' => 'overdue',
                ],
            ];

            foreach ($invoicesData as $inv) {
                Invoice::create($inv);
            }
        }
    }
}
