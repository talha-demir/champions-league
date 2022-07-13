<?php

namespace App\Classes;

use App\Interfaces\SoccerMatchRules;
use App\Models\Team;

class MatchContext {

    private SoccerMatchRules $soccerMatchRule;

    public function setRules(SoccerMatchRules $soccerMatchRule): void
    {
        $this->soccerMatchRule = $soccerMatchRule;
    }

    public function playSoccerMatch(Team $homeTeam, Team $awayTeam) {
        return $this->soccerMatchRule->calculateMatchResult($homeTeam, $awayTeam);
    }
}
