<?php

namespace App\Services;

use App\Classes\MatchContext;
use App\MatchRules\BasicMatchRule;
use App\Models\Fixture;
use App\Models\GameHistory;
use App\Models\Team;
use Illuminate\Support\Collection;

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

    private function playMatch(Fixture $fixture): void
    {
        $homeTeam = $fixture->homeTeam();
        $awayTeam = $fixture->awayTeam();

        $results = $this->matchContext->playSoccerMatch($homeTeam, $awayTeam);

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
              'team_id'        => $homeTeam->id,
              'fixture_id'     => $fixture->id,
            ];

            $gameHistories[] = [
              'goals_scored'   => $goals,
              'goals_conceded' => $goals,
              'drawn'          => true,
              'won'            => false,
              'game_points'    => $results[0],
              'points'         => 1,
              'team_id'        => $awayTeam->id,
              'fixture_id'     => $fixture->id,
            ];

            GameHistory::insert($gameHistories);

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
              'team_id'        => $homeTeam->id,
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
              'team_id'        => $awayTeam->id,
              'fixture_id'     => $fixture->id,
            ];

            GameHistory::insert($gameHistories);
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
              'team_id'        => $awayTeam->id,
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
              'team_id'        => $homeTeam->id,
              'fixture_id'     => $fixture->id,
            ];

            GameHistory::insert($gameHistories);
        }

        $fixture->is_completed = true;
        $fixture->save();
    }

    private function playFixtures(Collection $fixtures): void
    {
        foreach ($fixtures as $fixture)
        {
            $this->playMatch($fixture);
        }
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

    public function playNextWeekMatches(): bool
    {
        $nextWeekFixtures = $this->getNextWeekFixtures();

        if (is_null($nextWeekFixtures))
            return false;

        $this->playFixtures($nextWeekFixtures);

        return true;
    }

    public function playAllWeeks(): bool
    {
        $fixtures = Fixture::where('is_completed', false)->get();

        if (is_null($fixtures))
            return false;

        $this->playFixtures($fixtures);

        return true;
    }

    public function getChampionTeam(): Team|null
    {
        $fixtures = Fixture::where('is_completed', false)->get();
        $champion_team = null;

        if (!$fixtures->count())
        {
            $teams = Team::all();
            $champion_statistics = [];

            foreach ($teams as $team)
            {
                $team_statistics = $team->statistics();

                if (count($champion_statistics))
                {
                    if (($team_statistics["points"] > $champion_statistics["points"]))
                    {
                        $champion_statistics = $team->statistics();
                        $champion_team = $team;
                    }

                    if (($team_statistics["points"] === $champion_statistics["points"]) && ($team_statistics["goal_difference"] > $champion_statistics["goal_difference"]))
                    {
                            $champion_statistics = $team->statistics();
                            $champion_team = $team;
                    }
                }else
                {
                    $champion_statistics = $team->statistics();
                    $champion_team = $team;
                }
            }
        }

        return $champion_team;
    }

    public function resetData()
    {
        $gameHistories = GameHistory::get(["id"])->toArray();
        $fixtures = Fixture::get(["id"])->toArray();

        GameHistory::whereIn('id',$gameHistories)->delete();
        Fixture::whereIn('id',$fixtures)->delete();
    }

    public function isLeagueFinished(): bool
    {
        $fixtures = Fixture::where("is_completed", false)->get();
        if(!count($fixtures)) {
            return false;
        }
        return true;
    }

    public function getTeamNamesOfUncompletedFixtures(): Collection|null
    {
        return Fixture::where('is_completed', false)->get()
               ->groupBy('week')->map(function ($group) {
              return $group->map(function ($value) {
                  return [ "home_team_name" => Team::find($value->home_team_id)->name, "away_team_name" => Team::find($value->away_team_id)->name];
              });
          });
    }
}