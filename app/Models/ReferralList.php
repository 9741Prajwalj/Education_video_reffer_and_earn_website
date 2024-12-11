<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralList extends Model
{
    // Define the table associated with the model
    protected $table = 'referral_list';

    // Define the fields that are mass assignable
    protected $fillable = ['referral_name', 'referral_phone', 'user_id'];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
