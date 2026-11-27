<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

function createMergeUser(string $role): User
{
    return User::factory()->create([
        'username' => fake()->unique()->userName(),
        'phone' => '09170000000',
        'role' => $role,
    ]);
}

test('login sends each account role to its current dashboard', function (string $role, string $destination) {
    $user = createMergeUser($role);

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route($destination));

    $this->assertAuthenticatedAs($user);
})->with([
    'admin' => ['admin', 'pos.dashboard'],
    'staff' => ['staff', 'pos.dashboard'],
    'client' => ['client', 'member.portal'],
]);

test('the current dashboards render for their account roles', function (string $role, string $path, string $view) {
    $user = createMergeUser($role);

    $this->actingAs($user)->get($path)->assertOk()->assertViewIs($view);
})->with([
    'admin' => ['admin', '/pos', 'pos.admin-dashboard'],
    'staff' => ['staff', '/pos', 'pos.staff-dashboard'],
    'member' => ['client', '/member/portal', 'member.portal'],
]);

test('existing client pages remain available', function (string $path) {
    $user = createMergeUser('client');

    $this->actingAs($user)->get($path)->assertOk();
})->with(['/client/dashboard', '/client/store', '/client/bookings', '/client/progress', '/client/profile']);

test('account roles cannot enter another role workspace', function (string $role, string $path) {
    $user = createMergeUser($role);

    $this->actingAs($user)->get($path)->assertForbidden();
})->with([
    'staff cannot manage accounts' => ['staff', '/staff'],
    'client cannot enter the POS' => ['client', '/pos'],
    'staff cannot enter client tools' => ['staff', '/client/store'],
]);

test('a client can save their profile and return to the profile page', function () {
    $user = createMergeUser('client');

    $this->actingAs($user)->put('/client/profile', [
        'username' => 'updated-member',
        'email' => 'updated-member@example.test',
        'phone' => '09170000003',
    ])->assertSessionHasNoErrors()
        ->assertRedirect(route('client.profile.edit'))
        ->assertSessionHas('success', 'Profile updated.');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'updated-member',
        'username' => 'updated-member',
        'email' => 'updated-member@example.test',
        'phone' => '09170000003',
        'role' => 'client',
    ]);
});

test('registration creates a client and linked customer even when an admin role is submitted', function () {
    $this->post('/register', [
        'username' => 'new-member',
        'email' => 'member@example.test',
        'phone' => '09170000000',
        'password' => 'member-test-password',
        'password_confirmation' => 'member-test-password',
        'terms' => '1',
        'role' => 'admin',
    ])->assertRedirect(route('member.portal'));

    $user = User::where('email', 'member@example.test')->firstOrFail();
    expect($user->role)->toBe('client');
    expect(Hash::check('member-test-password', $user->password))->toBeTrue();
    $this->assertDatabaseHas('customers', ['user_id' => $user->id, 'email' => $user->email]);
});

test('an admin can create an employee login', function () {
    $admin = createMergeUser('admin');

    $this->actingAs($admin)->post('/staff', [
        'name' => 'Test Employee',
        'username' => 'test-employee',
        'email' => 'employee@example.test',
        'phone' => '09170000001',
        'role' => 'staff',
        'password' => 'employee-test-password',
        'password_confirmation' => 'employee-test-password',
    ])->assertSessionHasNoErrors()->assertRedirect();

    $employee = User::where('email', 'employee@example.test')->firstOrFail();
    expect($employee->role)->toBe('staff');
    expect(Hash::check('employee-test-password', $employee->password))->toBeTrue();
});

test('members can book sessions through the current portal', function () {
    $this->travelTo(Carbon::parse('2026-11-27 12:00:00'));
    $user = createMergeUser('client');
    $customer = Customer::create([
        'user_id' => $user->id, 'full_name' => $user->name, 'email' => $user->email,
    ]);

    $this->actingAs($user)->post('/member/bookings', [
        'session_type' => 'Open Gym', 'booking_date' => '2026-11-28', 'time_slot' => '09:00 AM',
    ])->assertSessionHasNoErrors()->assertRedirect(route('member.portal'));

    $this->assertDatabaseHas('bookings', ['customer_id' => $customer->id, 'status' => 'Confirmed']);
});

test('clients can request training through the existing booking page', function () {
    $this->travelTo(Carbon::parse('2026-11-27 12:00:00'));
    $user = createMergeUser('client');

    $this->actingAs($user)->post('/client/bookings', [
        'trainer_level' => 'novice', 'scheduled_at' => '2026-11-28 09:00:00', 'focus' => 'Strength',
    ])->assertSessionHasNoErrors()->assertRedirect(route('client.bookings'));

    $this->assertDatabaseHas('bookings', ['user_id' => $user->id, 'trainer_level' => 'novice', 'status' => 'pending']);
});

test('the merged POS retains cart checkout and updates stock', function () {
    $user = createMergeUser('staff');
    $product = Product::create(['name' => 'Drink', 'price' => '95.00', 'stock_quantity' => 5]);

    $this->actingAs($user)->post('/sales', [
        'items' => [['product_id' => $product->id, 'quantity' => 2]],
    ])->assertSessionHasNoErrors()->assertRedirect(route('pos.dashboard'));

    $this->assertDatabaseHas('sales', ['sold_by' => $user->id, 'product_id' => $product->id, 'quantity' => 2, 'total_price' => 190]);
    expect($product->fresh()->stock_quantity)->toBe(3);
});

test('overselling leaves the merged sales and stock unchanged', function () {
    $user = createMergeUser('staff');
    $product = Product::create(['name' => 'Drink', 'price' => '95.00', 'stock_quantity' => 1]);

    $this->actingAs($user)->post('/sales', [
        'items' => [['product_id' => $product->id, 'quantity' => 2]],
    ])->assertSessionHasErrors('items');

    $this->assertDatabaseCount('sales', 0);
    expect($product->fresh()->stock_quantity)->toBe(1);
});

test('role migrations preserve admin and staff accounts', function (string $role) {
    $user = createMergeUser($role);

    (require database_path('migrations/2026_11_21_080000_add_role_to_users_table.php'))->up();
    (require database_path('migrations/2026_11_21_080700_set_existing_users_to_clients.php'))->up();

    expect($user->fresh()->role)->toBe($role);
})->with(['admin', 'staff']);

test('existing employee accounts retain staff access after the merge migration', function () {
    $user = createMergeUser('employee');

    (require database_path('migrations/2026_11_27_131212_normalize_employee_roles.php'))->up();

    expect($user->fresh()->role)->toBe('staff');
});

test('existing training bookings survive schema reconciliation', function () {
    $user = createMergeUser('client');
    Schema::drop('bookings');
    (require database_path('migrations/2026_11_21_080500_create_bookings_table.php'))->up();
    $booking = Booking::create([
        'user_id' => $user->id, 'trainer_level' => 'elite', 'scheduled_at' => '2026-11-28 09:00:00', 'status' => 'pending',
    ]);

    (require database_path('migrations/2026_11_14_150328_create_bookings_table.php'))->up();
    (require database_path('migrations/2026_11_27_131135_reconcile_booking_schemas.php'))->up();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'user_id' => $user->id, 'status' => 'pending']);
    expect($booking->fresh()->scheduled_at->format('Y-m-d H:i:s'))->toBe('2026-11-28 09:00:00');
});

test('existing member bookings survive the additional booking migrations', function () {
    $user = createMergeUser('client');
    $customer = Customer::create([
        'user_id' => $user->id, 'full_name' => $user->name, 'email' => $user->email,
    ]);
    $booking = Booking::create([
        'customer_id' => $customer->id, 'session_type' => 'Open Gym',
        'booking_date' => '2026-11-28', 'time_slot' => '09:00 AM', 'status' => 'Confirmed',
    ]);

    (require database_path('migrations/2026_11_21_080500_create_bookings_table.php'))->up();
    (require database_path('migrations/2026_11_27_131135_reconcile_booking_schemas.php'))->up();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'customer_id' => $customer->id, 'status' => 'Confirmed']);
    expect($booking->fresh()->booking_date->format('Y-m-d'))->toBe('2026-11-28');
});

test('the merged seeder uses configured admin credentials', function () {
    config(['overclock.admin' => [
        'name' => 'Seed Admin', 'username' => 'seed-admin', 'email' => 'seed-admin@example.test',
        'phone' => '09170000002', 'password' => 'seed-test-password',
    ]]);

    $this->seed(DatabaseSeeder::class);

    $admin = User::where('email', 'seed-admin@example.test')->firstOrFail();
    expect($admin->role)->toBe('admin');
    expect(Hash::check('seed-test-password', $admin->password))->toBeTrue();
    $this->assertDatabaseHas('products', ['category' => 'month_pass']);
});
