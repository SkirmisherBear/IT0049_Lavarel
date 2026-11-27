<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests opening a logout redirect are sent to login', function () {
    $this->get('/logout?i=1')->assertRedirect(route('login'));
});

test('a logout redirect shows confirmation without ending the session', function (string $role) {
    $user = User::factory()->create(['username' => 'logout-user', 'phone' => '09170000000', 'role' => $role]);

    $this->actingAs($user)
        ->get('/logout?i=1')
        ->assertOk()
        ->assertViewIs('auth.logout')
        ->assertSee('Confirm log out')
        ->assertSee('method="POST"', false)
        ->assertSee('name="_token"', false);

    $this->assertAuthenticatedAs($user);
})->with(['admin', 'staff', 'client']);

test('confirming logout ends the session for each account role', function (string $role) {
    $user = User::factory()->create(['username' => 'logout-user', 'phone' => '09170000000', 'role' => $role]);

    $this->actingAs($user)
        ->withSession(['logout_test' => 'cleared on logout'])
        ->post(route('logout'))
        ->assertRedirect(route('login'))
        ->assertSessionMissing('logout_test');

    $this->assertGuest();
})->with(['admin', 'staff', 'client']);

test('logout is protected against requests without a csrf token', function () {
    $user = User::factory()->create(['username' => 'logout-user', 'phone' => '09170000000', 'role' => 'staff']);
    $this->app->instance('env', 'production');

    $this->actingAs($user)->post('/logout')->assertRedirect();

    $this->assertAuthenticatedAs($user);
});
