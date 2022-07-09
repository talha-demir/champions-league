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
    public function getTeamMoraleAttribute(): float
    {
        $lastGame = $this->gameHistory()->orderBy('week')->first();
        return $lastGame->won ? '' : $this->player_quality;
    }

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
