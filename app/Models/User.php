<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'occupation',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students');
    }

    public function certificat()
    {
        return $this->hasMany(Certificate::class);
    }
    public function subscribe_transactions()
    {
        return $this->hasMany(SubscribeTransaction::class);
    }
    public function hasActiveSubscriptionForCourse($courseId)
    {
        return $this->subscribe_transactions()
            ->where('course_id', $courseId)
            ->where('is_paid', true)
            ->exists();
    }

    public function hasActiveSubscription()
    {
        $latesSubscription = $this->subscribe_transactions()
            ->where('is_paid', true)
            ->latest('updated_at')
            ->first();

        if (!$latesSubscription) {
            return false;
        }

        $subscriptionEndDate = Carbon::parse($latesSubscription->subscription_start_date)->addMonth(1);
        return Carbon::now()->lessThanOrEqualTo($subscriptionEndDate);
    }
}
