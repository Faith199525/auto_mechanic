<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Role;

class AddDefaultDataToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {

        $defaultdata = [
                        [
                            'name' => 'shopOwner',
                            'permissions' => json_encode([
                                'create_shop','make_payment','create_user',
                                'update_user','view_user','delete_user','create_vehicle',
                                'update_vehicle','view_vehicle','delete_vehicle',
                                'create_vehicle_owner','view_vehicle_owner',
                                'update_vehicle_owner','delete_vehicle_owner',
                                'create_work_order','update_work_order','edit_work_order',
                                'print_work_order','create_invoice','approve_invoice',
                                'print_invoice','create_appointment','view_appointment',
                                'update_appointment','delete_appointment','generate_report'
                            ]),
                            'created_at' => now(+1),
                            'updated_at' => now(+1)
                        ],
                        [
                            'name' => 'technician', 
                            'permissions' => json_encode([
                                'view_vehicle_owner','create_work_order','view_vehicle','update_work_order','view_work_order'
                            ]),
                            'created_at' => now(+1),
                            'updated_at' => now(+1)
                        ],
                        [
                            'name' => 'accountant', 
                            'permissions' => json_encode([
                            'view_vehicle_owner','view_vehicle','create_invoice','update_invoice','view_invoice','print_invoice'
                            ]),
                            'created_at' => now(+1),
                            'updated_at' => now(+1)
                        ],
                        [
                            'name' => 'clerk', 
                            'permissions' => json_encode([
                                'create_vehicle_owner','create_vehicle','update_vehicle','view_vehicle'
                            ]),
                            'created_at' => now(+1),
                            'updated_at' => now(+1)
                        ],
                        [
                            'name' => 'superAdmin', 
                            'permissions' => json_encode(['all']),
                            'created_at' => now(+1),
                            'updated_at' => now(+1)
                        ]
                      ];

            if (Schema::hasTable('roles')) {
               
               // DB::table('roles')->insert($defaultdata);
                Role::insert($defaultdata);

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
        Schema::table('roles', function (Blueprint $table) {

            if (Schema::hasTable('roles')) {
                
               // DB::table('roles')->truncate();
               Role::truncate();

            }

        });
    }
}
