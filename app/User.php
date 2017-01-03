<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function generateNewApiKey()
    {
        $newApiKey = uniqid();
        if ($this->checkApiKey($newApiKey)) {
            $this->apiKey = $newApiKey;
            $this->save();
        }
    }

    private function checkApiKey($apiKey)
    {
        $users = $this->get();
        foreach($users as $user) {
            if ($user->apiKey == $apiKey) {
                return false;
            }
        }
        return true;
    }

    public function authorize($apiKey)
    {
        if ($user = $this->where('apiKey', $apiKey)->first()) {
            return $user;
        }
        abort(401);
    }
}
