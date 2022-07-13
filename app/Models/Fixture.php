<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'home_team_id',
      'away_team_id',
      'week',
      'match',
      'is_completed'
    ];

    public function homeTeam(): Team
    {
        return Team::find($this->home_team_id);
    }

    public function awayTeam(): Team
    {
        return Team::find($this->away_team_id);
    }

}
