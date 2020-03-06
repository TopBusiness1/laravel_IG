<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PolizzaCarMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polizza_car', function (Blueprint $table) {
            $table->integer('buyer_id')->nullable();
            $table->increments('id');
            $table->integer('procurement_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->date('date_request')->nullable();
            $table->date('date_policy_effect')->nullable();
            $table->date('date_policy_expire')->nullable();
            $table->date('order_sent_at')->nullable();
            $table->string('envelope_id')->nullable();
            
            // folder 2: Dati Aderente - map CSV export
            $table->string('company_name')->nullable(); // policy_holder_name
            $table->string('company_vat')->nullable(); // policy_holder_vat
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_address')->nullable(); // policy_holder_address
            $table->string('company_city')->nullable(); // policy_holder_city
            $table->string('company_cap')->nullable(); // policy_holder_postcode
            $table->string('company_provincia')->nullable(); // policy_holder_pr
            $table->integer('country_id')->nullable();

            $table->string('company_activity')->nullable();
            // referente e' la persona fisica riceve le mail di invito
            $table->string('referente_name')->nullable();
            $table->string('referente_email')->nullable();
            $table->string('referente_phone')->nullable();
            
            // folder 4: Demands & Needs - Questions

             // folder 5: Dati procurement & Needs - Questions
             $table->string('contract_code')->nullable(); // contract_code
             $table->string('works_type_id')->nullable(); // works_type_id
             $table->integer('works_type_details')->nullable(); // works_type_details
             $table->text('works_descr')->nullable(); // works_descr
             $table->string('works_duration_dd')->nullable(); // works_duration_dd
             $table->string('works_duration_mm')->nullable(); // works_duration_mm
             $table->string('works_place')->nullable(); //works_place
             $table->string('primary_works_place')->nullable(); //works_place
             
             $table->string('manteniance_coverage')->nullable(); // manteniance_coverage
             $table->string('cvg_decennial_liability')->nullable(); // cvg_decennial_liability
 
             $table->integer('risk_id')->nullable(); // lookup tariffe
                        
            $table->double('car_p1_limit_amount')->nullable(); // car_p1_limit_amount
            $table->double('car_p2_limit_amount')->nullable(); // car_p2_limit_amount
            $table->double('car_p3_limit_amount')->nullable(); // car_p3_limit_amount

            $table->double('coeff_tariffa')->nullable(); // coeff_tariffa
            $table->double('tax_rate')->nullable(); // coeff_tariffa

            $table->double('car_civil_liability')->nullable();

            $table->integer('group_id')->unsigned()->nullable(false)->default(1);
            $table->foreign('group_id')->references('id')->on('groups');

            $table->string('pdf_signed_contract')->nullable(); //works_place
            $table->string('pdf_payment_proof')->nullable(); //works_place
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polizza_car');
    }
}
