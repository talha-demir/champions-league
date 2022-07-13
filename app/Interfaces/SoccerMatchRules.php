<?php

namespace App\Interfaces;

use App\Models\Team;

interface SoccerMatchRules {
    public function calculateMatchResult(Team $a, Team $b): array;
}
