<?php

// app/Models/Admin.php
namespace App\Models\Admin;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password'];

    protected $hidden = ['password','remember_token'];
    public function getIsAdminAttribute()
    {
        // return $this->role === 'admin'; // Replace 'role' with your actual field for user roles
        return true;
    }

    // Relationship to notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}
