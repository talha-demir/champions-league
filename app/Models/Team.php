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
        return (is_null($this->lastGame) ||  $this->lastGame->won) ? 0 : $this->audience_support;
    }

    /**
     * @return float
     */
    public function getCalculatedMoraleAttribute(): float
    {
        return (is_null($this->lastGame) ||  $this->lastGame->won) ? 0 : $this->morale;
    }

    /**
     * @return Model|HasMany|null
     */
    public function getLastGameAttribute(): Model|HasMany|null
    {
        return $this->gameHistories()->orderBy('week')?->first();
    }

    /**
     * Get old matches
     *
     * @return HasMany
    */
    public function gameHistories(): HasMany
    {
        return $this->hasMany(GameHistory::class, 'team_id', 'id');
    }

    public function statistics(): array
    {
        $gameHistories = $this->gameHistories;
        $teamStatistics = [
          "played" => $gameHistories->count(),
          "goals_scored" => 0,
          "goals_conceded" => 0,
          "won" => 0,
          "drawn" => 0,
          "lost" => 0,
          "points" => 0,
        ];

        foreach ($gameHistories as $gameHistory)
        {
            $teamStatistics["goals_scored"] += $gameHistory->goals_scored;
            $teamStatistics["goals_conceded"] += $gameHistory->goals_conceded;
            $teamStatistics["won"] += $gameHistory->won;
            $teamStatistics["drawn"] += $gameHistory->drawn;
            $teamStatistics["points"] += $gameHistory->points;
            $teamStatistics["lost"] += ($gameHistory->won || $gameHistory->drawn) ? 0 : 1;
        }
        $teamStatistics["goal_difference"] = $teamStatistics["goals_scored"] - $teamStatistics["goals_conceded"];

        return $teamStatistics;
    }


}
