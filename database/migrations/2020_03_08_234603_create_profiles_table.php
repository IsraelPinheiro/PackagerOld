<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('profiles', function (Blueprint $table){
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable()->default(null);

            //Storage Limits -> This values can be override by the by the values set directly to the user
            $table->bigInteger('max_file_size')->unsigned()->default(0);    //Max size (In Bytes) of a given file. A value of 0 means there is no limit
            $table->bigInteger('max_package_size')->unsigned()->default(0); //Max total size (In Bytes) of a given package. A value of 0 means there is no limit
            $table->bigInteger('max_storage_size')->unsigned()->default(0); //The storage limit of the user (In Bytes). A value of 0 means there is no limit

            //ACLs
            //Reports
            $table->json('acl_reports_administrative');   //Read
            $table->json('acl_reports_operational');   //Read
            //Dashboards
            $table->json('acl_dashboards_management');   //Read
            $table->json('acl_dashboards_operational');   //Read
            //Audit
            $table->json('acl_audit_accessLogs');   //Read | Download
            $table->json('acl_audit_changeLogs');   //Read | Download
            //System
            $table->json('acl_users');      //Create | Read | Update | Delete
            $table->json('acl_profiles');   //Create | Read | Update | Delete
            //Security and Audit
            $table->json('acl_backups');    //Create | Read | Download | Restore | Delete
            $table->json('acl_config');    //Read | Update
            
            //TODO: Add remaining ACL Rules

            $table->timestamps();   //created_at e updated_at
            $table->softDeletes();  //deleted_at
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('profiles');
    }
}
