<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'description',
        'value',
        'limit',
    ];

    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }
}
