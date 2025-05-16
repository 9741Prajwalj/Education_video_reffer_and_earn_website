<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Otp extends Model
{
    use HasFactory;

    // Define the table name (if different from the default 'otps')
    protected $table = 'otps';

    // Allow mass assignment for these fields
    protected $fillable = ['email', 'otp', 'expires_at'];

    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }
    // Set the timestamps for created_at and updated_at automatically
    public $timestamps = true;

    // If you want to set default values for created_at and updated_at
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;  // We don't need updated_at for OTP records
}
