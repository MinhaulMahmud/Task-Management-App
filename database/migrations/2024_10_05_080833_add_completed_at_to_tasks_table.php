<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->timestamp('completed_at')->nullable()->after('image'); // Add this line
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->dropColumn('completed_at'); // Drop the column if needed
    });
}

};
