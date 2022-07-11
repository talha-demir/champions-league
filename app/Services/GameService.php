<?php

namespace App\Services;

use App\Classes\MatchContext;
use App\MatchRules\BasicMatchRule;
use App\Models\Fixture;
use App\Models\GameHistory;
use App\Models\Team;

/**
 * Class Services
 *
 * @package App\Services
 */
class GameService
{
    const EPSILON = 0.5;
    private MatchContext $matchContext;

    function __construct()
    {
        $this->matchContext = new MatchContext();
        $this->matchContext->setRules(new BasicMatchRule());
    }

    public function playMatch(Team $a, Team $b, Fixture $fixture)
    {
        $results = $this->matchContext->playSoccerMatch($a, $b);

        if (abs($results[0] - $results[1]) < self::EPSILON) {
            // draw state
            $goals = rand(1, 5);

            $gameHistories[] = [
              'goals_scored'   => $goals,
              'goals_conceded' => $goals,
              'drawn'          => true,
              'won'            => false,
              'game_points'    => $results[0],
              'points'         => 1,
              'team_id'        => $a->id,
              'fixture_id'     => $fixture->id,
            ];

            $gameHistories[] = [
              'goals_scored'   => $goals,
              'goals_conceded' => $goals,
              'drawn'          => true,
              'won'            => false,
              'game_points'    => $results[0],
              'points'         => 1,
              'team_id'        => $b->id,
              'fixture_id'     => $fixture->id,
            ];

            GameHistory::upsert($gameHistories);

        } elseif ($results[0] > $results[1]) {
            // team a won
            $x = rand(1, 5);
            $y = rand(0,$x);

            //A team history
            $gameHistories[] = [
              'goals_scored'   => $x,
              'goals_conceded' => $y,
              'drawn'          => false,
              'won'            => true,
              'game_points'    => $results[0],
              'points'         => 3,
              'team_id'        => $a->id,
              'fixture_id'     => $fixture->id,
            ];

            //B team history
            $gameHistories[] = [
              'goals_scored'   => $y,
              'goals_conceded' => $x,
              'drawn'          => false,
              'won'            => false,
              'game_points'    => $results[1],
              'points'         => 0,
              'team_id'        => $b->id,
              'fixture_id'     => $fixture->id,
            ];

            GameHistory::upsert($gameHistories);
        } else {
            // Team B won
            $x = rand(1, 5);
            $y = rand(0,$x);

            //B team history
            $gameHistories[] = [
              'goals_scored'   => $x,
              'goals_conceded' => $y,
              'drawn'          => false,
              'won'            => true,
              'game_points'    => $results[1],
              'points'         => 3,
              'team_id'        => $b->id,
              'fixture_id'     => $fixture->id,
            ];

            //B team history
            $gameHistories[] = [
              'goals_scored'   => $x,
              'goals_conceded' => $y,
              'drawn'          => false,
              'won'            => false,
              'game_points'    => $results[0],
              'points'         => 0,
              'team_id'        => $a->id,
              'fixture_id'     => $fixture->id,
            ];

            GameHistory::upsert($gameHistories);
        }

        $fixture->is_completed = true;
        $fixture->save();

        return $fixture;
    }

    public function getNextWeekFixtures()
    {
        $playedWeek = Fixture::where('is_completed', true)->max('week');
        if (is_null($playedWeek))
        {
            return Fixture::where('week', 0)->get();
        }
        return Fixture::where('is_completed', false)->where('week', $playedWeek + 1)->get();
    }
}