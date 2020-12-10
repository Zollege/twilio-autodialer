<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class VerifiedPhoneNumber extends Model
{
    use Eloquence;

    protected $fillable = ['phone_number', 'friendly_name'];

    protected $searchableColumns = ['phone_number', 'friendly_name'];
    /**
     *  A Verified Caller ID belongs to a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
