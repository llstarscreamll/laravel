<?php

use Illuminate\Database\Seeder;
use llstarscreamll\Authorization\Models\Permission;

/**
 * Class EmployeesPermissionsSeeder.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class EmployeesPermissionsSeeder extends Seeder
{
    /**
     * @var array
     */
    private $permissions = [
        ['name' => 'employees.search'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect($this->permissions)->map(function ($permission) {
            return Permission::updateOrCreate($permission, $permission);
        });
    }
}
