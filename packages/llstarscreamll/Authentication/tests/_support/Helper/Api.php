<?php
namespace Authentication\Helper;

use llstarscreamll\Users\Models\User;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module
{
    /**
     * Create and log in the admin user.
     *
     * @return App\Containers\User\Models\User
     */
    public function amLoggedAsUser($user = null, string $driver = 'api')
    {
        if (is_array($user)) {
            $user = User::create($user);
        }

        if (is_null($user)) {
            $user = User::create([
                'name'     => 'admin',
                'email'    => 'admin@admin.com',
                'password' => bcrypt('admin'),
            ]);
        }

        return $this->loginUser($user, $driver);
    }

    /**
     * Log in the given user.
     *
     * @param  \llstarscreamll\Users\Models\User   $user
     * @return \llstarscreamll\Users\Models\User
     */
    public function loginUser(User $user, string $driver = 'api')
    {
        app('auth')->guard($driver)->setUser($user);
        app('auth')->shouldUse($driver);

        return $user;
    }
}
