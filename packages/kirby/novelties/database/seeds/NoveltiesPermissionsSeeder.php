<?php

use Illuminate\Database\Seeder;
use Kirby\Authorization\Models\Permission;

/**
 * Class NoveltiesPermissionsSeeder.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class NoveltiesPermissionsSeeder extends Seeder
{
    /**
     * @var array
     */
    private $permissions = [
        // novelties
        ['name' => 'novelties.get'],
        ['name' => 'novelties.search'],
        ['name' => 'novelties.update'],
        ['name' => 'novelties.create-novelties-to-users'],
        // novelty approvals
        ['name' => 'novelties.approvals.create'],
        ['name' => 'novelties.approvals.delete'],
        // novelty types
        ['name' => 'novelty-types.search'],
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