<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'message',
        'user_id',
        'seen_at',
    ];
    // Relationship to the User and Admin
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scope to get notifications for the current user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

}
