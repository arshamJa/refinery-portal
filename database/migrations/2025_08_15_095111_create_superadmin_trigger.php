<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER prevent_superadmin_role_delete
            BEFORE DELETE ON role_user
            FOR EACH ROW
            BEGIN
                DECLARE super_admin_id BIGINT;
                SELECT id INTO super_admin_id FROM users WHERE p_code='Samael';
                IF OLD.user_id = super_admin_id THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot remove super admin role.';
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS prevent_superadmin_role_delete");
    }
};
