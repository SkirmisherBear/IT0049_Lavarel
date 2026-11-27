<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Preserve employee access using the role name assigned by the current staff form.
     */
    public function up(): void
    {
        DB::table('users')->where('role', 'employee')->update(['role' => 'staff']);
    }

    /**
     * Existing staff accounts cannot be distinguished from renamed employee accounts.
     */
    public function down(): void {}
};
