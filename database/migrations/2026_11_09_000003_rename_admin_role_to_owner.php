<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        $admin = DB::table('roles')->where('name', 'admin')->first();
        $owner = DB::table('roles')->where('name', 'owner')->first();

        if ($admin && !$owner) {
            DB::table('roles')->where('id', $admin->id)->update(['name' => 'owner']);
        } elseif ($admin && $owner) {
            if (Schema::hasTable('role_user')) {
                $assignments = DB::table('role_user')->where('role_id', $admin->id)->get();
                foreach ($assignments as $assignment) {
                    $alreadyAssigned = DB::table('role_user')
                        ->where('role_id', $owner->id)
                        ->where('user_id', $assignment->user_id)
                        ->exists();
                    if (!$alreadyAssigned) {
                        DB::table('role_user')->insert(['role_id' => $owner->id, 'user_id' => $assignment->user_id]);
                    }
                }
                DB::table('role_user')->where('role_id', $admin->id)->delete();
            }
            DB::table('roles')->where('id', $admin->id)->delete();
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles')) {
            DB::table('roles')->where('name', 'owner')->update(['name' => 'admin']);
        }
    }
};
