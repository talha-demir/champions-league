<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'name',
      'player_quality',
      'audience_support',
      'morale'
    ];

    /**
     * @return float
    */
    public function getCoachQualityAttribute(): float
    {
        //calculated by adding 70% of player quality and 30% of a random number generated between 1 and 3
        return $this->player_quality * 0.7 + ((rand(100,300)/100) * 0.3);
    }

    /**
     * @return float
     */
    public function getAudienceAttribute(): float
    {
        return $this->lastGame->won ? 0 : $this->audience_support;
    }

    /**
     * @return Model|HasMany
     */
    public function getLastGameAttribute(): Model|HasMany
    {
        return $this->gameHistory()->orderBy('week')->first();
    }

//    /**
//     * @return Model|HasMany
//     */
//    public function getLastWeekFixtureAttribute(): Model|HasMany
//    {
//        return $this->gameHistory()->orderBy('week')->first();
//    }

    /**
     * Get old matches
     *
     * @return HasMany
    */
    public function gameHistory(): HasMany
    {
        return $this->hasMany(GameHistory::class, 'team_id', 'id');
    }


}
