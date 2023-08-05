<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->decimal('receipt', 5, 2);
            $table->foreignId('member_id')->constrained();
            $table->foreignId('cashier_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
