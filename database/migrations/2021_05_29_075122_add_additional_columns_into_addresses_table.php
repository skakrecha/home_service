<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalColumnsIntoAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('property_type')->nullable();
            $table->unsignedInteger('number_of_gas_appliances')->nullable();
            $table->unsignedInteger('number_of_electric_appliances')->nullable();
            $table->unsignedInteger('number_of_bedrooms')->nullable();
            $table->unsignedInteger('number_of_reception_room')->nullable();
            $table->string('boiler_type')->nullable();
            $table->string('furnished_type')->nullable();
            $table->string('outside_space')->nullable();
            $table->string('parking')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('property_type');
            $table->dropColumn('number_of_gas_appliances');
            $table->dropColumn('number_of_electric_appliances');
            $table->dropColumn('number_of_bedrooms');
            $table->dropColumn('number_of_reception_room');
            $table->dropColumn('boiler_type');
            $table->dropColumn('furnished_type');
            $table->dropColumn('outside_space');
            $table->dropColumn('parking');
            $table->dropColumn('notes');
        });
    }
}
