<?php

namespace App\Models\Admin;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;


class Admin extends Authenticatable
{
    use HasRoles,HasApiTokens;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'photo',
        'role_id',
        'latitude',
        'longitude',
        'mobile',
        'company_id'
    ];

    public function profile(){
        return $this->hasMany(Profile::class,'user_id');
    }

    public function parent(){
        return $this->belongsTo(Admin::class, 'parent_id');
    }
  	
  	public function channel(){
        return $this->hasMany(Channel_integration::class,'user_id');
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }
    
    public function getKycStatusAttribute($value)
    {
        return [
        '0' => '<span class="badge text-white bg-warning">Yet To Fill</span>',
        '1' => '<span class="badge text-white bg-secondary">Pending</span>',
        '2' => '<span class="badge text-white bg-success">Approved</span>',
        '3' => '<span class="badge text-white bg-danger">Rejected</span>',
        ][$value];
    }
    
    public static function getsubuserid($user_id){
        if($user_id =='' || $user_id == '0'){
            return array(); 
        }else{
            $user_d = array($user_id);
            $subuser = Admin::where('parent_id',$user_id)->where('delete_status',0)->get();
            foreach($subuser as $user):
                $user_d[] = $user->id;
            endforeach;
            return $user_d;
        }
    }

    public static function getsubusername($user_id){
        if($user_id =='' || $user_id == '0'){
            return array(); 
        }else{
            $subuser = Admin::where('delete_status',0)
            ->where(function($query) use ($user_id){
                $query->where('parent_id',$user_id)
                ->orWhere('id',$user_id);
                })
            ->get();
            foreach($subuser as $user):
                $user_d[] = $user->name;
            endforeach;
            return $user_d;
        }
    }
    
    public static function getusername($user_id){
        $subuser = Admin::where('id',$user_id)->first();
        return $subuser->name;
    }
    
    public static function chkdublictaegst($user_id){
        $ordermatch = Profile::where('user_id',$user_id)->select('gst')->first();
        if($ordermatch){
            $orderdata = Profile::where('user_id','!=', $user_id)
            ->where("gst", $ordermatch->gst)
            ->first();
            if($orderdata){
                return 'yes';
            }else{
                return  'no';
            }
        }else{
            return  'no';
        }
    }
    
    public static function generateUniqueUserCode()
    {
        do {
            $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('user_code', $code)->exists());
    
        return $code;
    }

}
