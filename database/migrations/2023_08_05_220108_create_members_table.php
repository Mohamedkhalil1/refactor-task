<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            //            $table->string('gender', 10);
            $table->boolean('is_male')->default(true);
            $table->string('email')->unique();
            $table->string('phone', 20)->unique();

            // visit_count
            $table->unsignedInteger('visit_count')->default(0)->index();

            // we can add last_visit_at to get the last visit date here
            // but we can get it from the last visit record

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
