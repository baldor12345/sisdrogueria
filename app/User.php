<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\UserResetPasswordNotification;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'empresa_id','login', 'password', 'state', 'persona_id','usertype_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopelistar($query, $login)
    {
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        return $query->where(function($subquery) use($login)
                    {
                        if (!is_null($login)) {
                            $subquery->where('login', 'LIKE', '%'.$login.'%');
                        }
                    })
                    ->where('empresa_id', $empresa_id)
                    ->orderBy('login', 'ASC');
    }

    public function usertype()
    {
        return $this->belongsTo('App\Usertype', 'usertype_id');
    }

    public function persona(){
        return $this->belongsTo('App\Persona', 'persona_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }
}
