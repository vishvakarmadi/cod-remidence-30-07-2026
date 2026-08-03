<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodFieldsToArchiveOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archive_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('archive_orders', 'cod_paid_amount')) {
                $table->string('cod_paid_amount')->nullable()->after('cod_amount');
            }
            if (!Schema::hasColumn('archive_orders', 'cod_transaction_date')) {
                $table->string('cod_transaction_date')->nullable()->after('cod_paid_amount');
            }
            if (!Schema::hasColumn('archive_orders', 'cod_utr')) {
                $table->string('cod_utr')->nullable()->after('cod_transaction_date');
            }
            if (!Schema::hasColumn('archive_orders', 'cod_remark')) {
                $table->text('cod_remark')->nullable()->after('cod_utr');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archive_orders', function (Blueprint $table) {
            $table->dropColumn(['cod_paid_amount', 'cod_transaction_date', 'cod_utr', 'cod_remark']);
        });
    }
}
