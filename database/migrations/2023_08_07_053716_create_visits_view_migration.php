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
       DB::statement("
         create view visits_view as (
            select
                visits.id , visits.receipt , visits.member_id , visits.cashier_id , visits.created_at,
                concat(coalesce(members.first_name , '') , ' ' , coalesce(members.last_name , ''))  as member_full_name,
                members.phone as member_phone, cashiers.name as cashier_name,
                (select max(loyalty.points)  from loyalty where visits.id = loyalty.visit_id) as max_points
            from visits
            left join members on visits.member_id = members.id
            left join cashiers on visits.cashier_id = cashiers.id
         );
       ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits_view');
    }
};
