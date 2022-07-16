<?php

namespace App\Services;

use App\Classes\MatchContext;
use App\MatchRules\BasicMatchRule;
use App\Models\Fixture;
use App\Models\GameHistory;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param Fixture $fixture
     *
     * @return void
     */
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
              'week'           => $fixture->week,
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
              'week'           => $fixture->week,
              'game_points'    => $results[0],
              'points'         => 1,
              'team_id'        => $awayTeam->id,
              'fixture_id'     => $fixture->id,
            ];

            GameHistory::insert($gameHistories);

        } elseif ($results[0] > $results[1]) {
            // team a won
            $x = rand(1, 5);
            $y = rand(0,$x-1);

            //A team history
            $gameHistories[] = [
              'goals_scored'   => $x,
              'goals_conceded' => $y,
              'drawn'          => false,
              'won'            => true,
              'week'           => $fixture->week,
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
              'week'           => $fixture->week,
              'game_points'    => $results[1],
              'points'         => 0,
              'team_id'        => $awayTeam->id,
              'fixture_id'     => $fixture->id,
            ];

            GameHistory::insert($gameHistories);
        } else {
            // Team B won
            $x = rand(1, 5);
            $y = rand(0,$x-1);

            //B team history
            $gameHistories[] = [
              'goals_scored'   => $x,
              'goals_conceded' => $y,
              'drawn'          => false,
              'won'            => true,
              'week'           => $fixture->week,
              'game_points'    => $results[1],
              'points'         => 3,
              'team_id'        => $awayTeam->id,
              'fixture_id'     => $fixture->id,
            ];

            //B team history
            $gameHistories[] = [
              'goals_scored'   => $y,
              'goals_conceded' => $x,
              'drawn'          => false,
              'won'            => false,
              'week'           => $fixture->week,
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

    /**
     * @param Collection $fixtures
     *
     * @return void
     */
    private function playFixtures(Collection $fixtures): void
    {
        foreach ($fixtures as $fixture)
        {
            $this->playMatch($fixture);
        }
    }

    /**
     * @return mixed
     */
    public function getNextWeekFixtures(): mixed
    {
        $playedWeek = Fixture::where('is_completed', true)->max('week');
        if (is_null($playedWeek))
        {
            return Fixture::where('week', 0)->get();
        }

        return Fixture::where('is_completed', false)->where('week', $playedWeek + 1)->get();
    }

    /**
     * @return null
     */
    public function getLastWeekFixtures()
    {
        $playedWeek = Fixture::where('is_completed', true)->max('week');
        if (is_null($playedWeek))
        {
            return null;
        }

        return Fixture::where('is_completed', true)->where('week', $playedWeek)->get();
    }

    /**
     * @return bool
     */
    public function playNextWeekMatches(): bool
    {
        $nextWeekFixtures = $this->getNextWeekFixtures();

        if (is_null($nextWeekFixtures))
            return false;

        $this->playFixtures($nextWeekFixtures);

        return true;
    }

    /**
     * @return bool
     */
    public function playAllWeeks(): bool
    {
        $fixtures = Fixture::where('is_completed', false)->get();

        if (is_null($fixtures))
            return false;

        $this->playFixtures($fixtures);

        return true;
    }

    /**
     * @return Team|null
     */
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

    /**
     * @return void
     */
    public function resetData(): void
    {
        $gameHistories = GameHistory::get(["id"])->toArray();
        $fixtures = Fixture::get(["id"])->toArray();

        GameHistory::whereIn('id',$gameHistories)->delete();
        Fixture::whereIn('id',$fixtures)->delete();
    }

    /**
     * @return bool
     */
    public function isLeagueFinished(): bool
    {
        $fixtures = Fixture::where("is_completed", false)->get();
        if(!count($fixtures)) {
            return false;
        }
        return true;
    }

    /**
     * @return Collection|null
     */
    public function getTeamNamesOfUncompletedFixtures(): Collection|null
    {
        return Fixture::where('is_completed', false)->get()
               ->groupBy('week')->map(function ($group) {
              return $group->map(function ($value) {
                  return [ "home_team_name" => Team::find($value->home_team_id)->name, "away_team_name" => Team::find($value->away_team_id)->name];
              });
          });
    }

    /**
     * @return Collection|null
     */
    public function getLastWeekResults(): Collection|null
    {
        $lastWeekfixtures = $this->getLastWeekFixtures();

        if ($lastWeekfixtures) {
            return $lastWeekfixtures->map(function ($value) {
                return [
                  "home_team_goals" => GameHistory::where('fixture_id',$value->id)->where('week', $value->week)->where('team_id', $value->home_team_id)->get()->first()->goals_scored,
                  "home_team_name" => Team::find($value->home_team_id)->name,
                  "away_team_goals" => GameHistory::where('fixture_id',$value->id)->where('week', $value->week)->where('team_id', $value->away_team_id)->get()->first()->goals_scored,
                  "away_team_name" => Team::find($value->away_team_id)->name,
                  "week" => $value->week
                ];
            });
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function predictions(): ?array
    {
        $teams = Team::get();
        $totalWeeks = $teams->count();
        $lastFixtures =  Fixture::where('is_completed', true)->get();
        $nextFixtures =  Fixture::where('is_completed', false)->get();

        if ((count($lastFixtures) && $lastFixtures->last()->week + 1 > $totalWeeks/2) && count($nextFixtures))
        {
            $gameHistories = DB::table('game_histories')->select(DB::raw('SUM(points) as points, team_id'))->groupBy('team_id')->get();
            $teamPoints = [];
            $championTeam = [];
            $teamResults = [];

            foreach ($gameHistories as $gameHistory)
            {
                $teamPoints[$gameHistory->team_id] = $gameHistory->points;
                $championTeam[$gameHistory->team_id] = 0;
                $teamResults[$gameHistory->team_id]['team_name'] = '';
                $teamResults[$gameHistory->team_id]['percentage'] = 0;
            }

            for($i = 0; $i < 10; $i++)
            {
                foreach ($nextFixtures as $fixture)
                {
                    $results = $this->matchContext->playSoccerMatch($fixture->homeTeam(), $fixture->awayTeam());
                    if (abs($results[0] - $results[1]) < self::EPSILON) {
                        $teamPoints[$fixture->homeTeam()->id] += 1;
                        $teamPoints[$fixture->awayTeam()->id] += 1;
                    } elseif ($results[0] > $results[1])
                    {
                        $teamPoints[$fixture->homeTeam()->id] += 3;
                    } else
                    {
                        $teamPoints[$fixture->awayTeam()->id] += 3;
                    }
                }
                $teamPoints = collect($teamPoints)->sortByDesc(function ($value){
                    return $value;
                })->toArray();

                foreach ($gameHistories as $gameHistory)
                {
                    $teamPoints[$gameHistory->team_id] = $gameHistory->points;
                }

                $championTeam[array_keys($teamPoints)[0]] += 1;
            }

            $results = $this->softmax($championTeam);

            foreach ($results as $key => $result)
            {
                $teamResults[$key]['team_name'] = Team::find($key)->name;
                $teamResults[$key]['percentage'] = 100 * $result;
            }

            return $teamResults;
        }

        return null;
    }

    /**
     * @param array $v
     *
     * @return array
     */
    private function softmax(array $v): array
    {
        $v = array_map('exp',array_map('floatval',$v));
        $sum = array_sum($v);

        foreach($v as $index => $value) {
            $v[$index] = $value/$sum;
        }

        return $v;
    }
}