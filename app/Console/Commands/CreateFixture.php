<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateFixture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:fixture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fixture based on teams count';

    public function teamName($num, $names): string
    {
        $i = $num - 1;
        if (count($names) > $i && strlen(trim($names[$i])) > 0) {
            return trim($names[$i]);
        } else {
            return $num;
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $teams = Team::all();
        $teamsCount = $teams->count();
        $teamNames = DB::table('teams')
            ->select('name')
            ->get()
            ->pluck('name');

        // If odd number of teams add a "ghost".
        $ghost = false;
        if ($teamsCount % 2 == 1) {
            $teamsCount++;
            $ghost = true;
        }

        $totalWeeks = $teamsCount - 1;
        $matchesPerWeek = $teamsCount / 2;
        $weeks = [];
        for ($i = 0; $i < $totalWeeks; $i++) {
            $weeks[$i] = [];
        }

        for ($week = 0; $week < $totalWeeks; $week++) {
            for ($match = 0; $match < $matchesPerWeek; $match++) {
                $home = ($week + $match) % ($teamsCount - 1);
                $away = ($teamsCount - 1 - $match + $week) % ($teamsCount - 1);
                // Last team stays in the same place while the others
                // rotate around it.
                if ($match == 0) {
                    $away = $teamsCount - 1;
                }

                $weeks[$week][$match] = $this->teamName($home + 1, $teamNames) . " v " . $this->teamName($away + 1, $teamNames);

                Fixture::create([
                                  'home_team_id' => $teams->slice($home, 1)->first()->id,
                                  'away_team_id' => $teams->slice($away, 1)->first()?->id,
                                  'week'         => $week,
                                  'match'        => $match,
                                ]);

            }
        }

        // Interleave so that home and away games are fairly evenly dispersed.
        $interleaved = [];
        for ($i = 0; $i < $totalWeeks; $i++) {
            $interleaved[$i] = [];
        }

        $evn = 0;
        $odd = ($teamsCount / 2);
        for ($i = 0; $i < count($weeks); $i++) {
            if ($i % 2 == 0) {
                $index = $evn++;
                $fixtures = Fixture::where('week', $i)->get();
                foreach ($fixtures as $fixture)
                {
                    $fixture->week = $index;
                }

            } else {
                $index = $odd++;
                $fixtures = Fixture::where('week', $i)->get();
                foreach ($fixtures as $fixture)
                {
                    $fixture->week = $index++;
                }
            }
        }

        $weeks = $interleaved;

        // Last team can't be away for every game so flip them
        // to home on odd rounds.
        for ($week = 0; $week < count($weeks); $week++) {
            if ($week % 2 == 1) {
                //$weeks[$week][0] = $this->flip($weeks[$week][0]);

                $fixture = Fixture::where('week', $week)->where('match', 1)->first();
                $home_team_id = $fixture->home_team_id;
                $fixture->home_team_id = $fixture->away_team_id;
                $fixture->away_team_id = $home_team_id;
                $fixture->save();
            }
        }

        $week_counter = count($weeks);

        for ($i = count($weeks) - 1; $i >= 0; $i--) {
            foreach ($weeks[$i] as $r) {
                $key = array_search($r, $weeks[$i]);

                $fixture = Fixture::where('week', $i)->where('match', $key)->first();

                Fixture::create([
                                  'home_team_id' => $fixture->away_team_id,
                                  'away_team_id' => $fixture->home_team_id,
                                  'week'         => $week_counter,
                                  'match'        => $key,
                                ]);
            }
            $week_counter += 1;
        }
        return 0;
    }
}
