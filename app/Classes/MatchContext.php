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

    public function playSoccerMatch(Team $a, Team $b) {
        return $this->soccerMatchRule->calculateMatchResult($a, $b);
    }
}
