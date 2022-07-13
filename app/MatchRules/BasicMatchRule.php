<?php

namespace App\MatchRules;

use App\Interfaces\SoccerMatchRules;
use App\Models\Team;

class BasicMatchRule implements SoccerMatchRules {

    const PLAYER_QUALITY = 0.22;
    const COACH = 0.19;
    const TEAM_MORALE = 0.18;
    const AUDIENCE_SUPPORT = 0.16;
    const CHANCE = 0.14;
    const REFEREE = 0.11;

    public function calculateMatchResult(Team $homeTeam, Team $awayTeam): array
    {
        $results[] = $this->calculateTeamPoints($homeTeam);
        $results[] = $this->calculateTeamPoints($awayTeam);

        return $results;
    }

    private function calculateTeamPoints(Team $team): float|int
    {
        $team_points = 0;
        $team_points += $team->player_quality * self::PLAYER_QUALITY;
        $team_points += $team->audience * self::AUDIENCE_SUPPORT;
        $team_points += $team->coachQuality * self::COACH;
        $team_points += $team->calculatedMorale * self::TEAM_MORALE;
        $team_points += (rand(100,1000)/100) * self::CHANCE;
        $team_points += (rand(100,1000)/100) * self::REFEREE;

        return $team_points;
    }




}