<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getRandPassword()
    {

        $string1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $string2 = "abcdefghijklmnopqrstuvwxyz";
        $string3 = "0123456789";
        $string4 = "$@";
        $string5 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789$@";

        $n = rand(0, strlen($string1) - 1);
        $rand_pwd =  $string1[$n];

        for ($i = 0; $i < 2; $i++) {
            $n = rand(0, strlen($string2) - 1);
            $rand_pwd .=  $string2[$n];
        }

        $n = rand(0, strlen($string3) - 1);
        $rand_pwd .=  $string3[$n];

        $n = rand(0, strlen($string4) - 1);
        $rand_pwd .=  $string4[$n];

        for ($i = 0; $i < 3; $i++) {
            $n = rand(0, strlen($string5) - 1);
            $rand_pwd .=  $string5[$n];
        }

        return $rand_pwd;
    }

    public static function sendMail($tomail, $cc, $bcc, $subject, $content)
    {
        $mail_queue = new MailQueue;
        $mail_queue->mailto = $tomail;
        $mail_queue->subject = $subject;
        $mail_queue->content = $content;
        $mail_queue->save();
    }

    public static function checkPermission($id)
    {
        $permissions = DB::table('user_permissions')->where('user_id', Auth()->user()->id)->pluck('access_right_id')->toArray();

        if (in_array($id, $permissions)) {
            return true;
        } else {
            return false;
        }
    }

    public static function AuthenticateUser($api_token){
        if(!$api_token || $api_token == NULL){
            die("user not found");
        } else {

            $user = Cache::remember("user-".$api_token, 2*60, function() use ($api_token){

                $user = DB::table("users")->select("id","name","api_token","privilege","client_id")->where('api_token',$api_token)->where("is_active",1)->first();

                if($user){

                }

                return $user;
            });

            if($user){
                return $user;
            } else {
                die("user not found");
            }
        }
    }
}
