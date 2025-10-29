<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique()->index();
            $table->date('invoice_date');
            $table->decimal('sub_total', 14, 2);
            $table->decimal('tax_rate', 5, 2)->default(10);
            $table->decimal('tax_amount', 14, 2);
            $table->decimal('total', 14, 2);
            $table->enum('status', ['draft', 'issued', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
