<div class="bg-white">
    <div class="grid grid-cols-6 gap-4 mt-5 mx-4 py-8">
        <div class="overflow-x-auto col-span-4">
            <div class="inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center">
                        <thead class="border-b bg-gray-800">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4 text-left">
                                Team Name
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                Played
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                Won
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                Drawn
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                Lost
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                GF
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                GA
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                GD
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                Points
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teams as $team)
                            <tr class="bg-white border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap text-left">
                                    {{$team->name}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$team->statistics()["played"]}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$team->statistics()["won"]}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$team->statistics()["drawn"]}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$team->statistics()["lost"]}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$team->statistics()["goals_scored"]}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$team->statistics()["goals_conceded"]}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$team->statistics()["goal_difference"]}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{$team->statistics()["points"]}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($nextWeekFixtures->count())
            <div class="rounded-lg shadow-lg bg-white max-h-48 col-span-2">
                <h5 class="text-xl font-medium mb-2 border-b bg-gray-800 p-2 text-white">Week {{ $nextWeekFixtures->first()->week + 1}}</h5>
                <div class="p-6">
                    @foreach($nextWeekFixtures as $key => $nextWeekFixture)
                        <p class="text-gray-700 text-base mb-4">
                            {{$nextWeekFixture->homeTeam()->name}} - {{$nextWeekFixture->awayTeam()->name}}
                        </p>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex justify-center col-span-2">
                <div class="rounded-lg shadow-lg bg-white max-w-sm w-full">
                    <a href="#!">
                        <div style="background-color: #0310b0" class="">
                            <img class="rounded-t-lg w-24 h-32 m-auto" src="https://img.uefa.com/imgml/uefacom/ucl/2021/logos/logo_dark.svg" alt=""/>
                        </div>
                    </a>
                    <div class="p-6">
                        <h5 class="text-gray-900 text-xl font-medium mb-2 text-center">Champion</h5>
                        <p class="text-gray-700 text-base mb-4 text-center m-auto">
                            {{$championTeam->name}}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="overflow-x-auto max-w-2xl col-span-3">
            <div class="inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center">
                        <thead class="border-b bg-gray-800">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4 text-left">
                                Championship Predictions
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                %
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teams as $team)
                            <tr class="bg-white border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap text-left">
                                    {{$team->name}}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    0
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto col-span-3 px-32">
        <div class="inline-block min-w-full py-8">
            <div class="flex justify-between">

                @if($nextWeekFixtures->count() > 0)
                    <button wire:click="playAllWeeks" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="inline-block px-8 py-3 bg-blue-400 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-500 hover:shadow-lg focus:bg-blue-500 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-600 active:shadow-lg transition duration-150 ease-in-out">
                        Play All Weeks
                    </button>


                    <button wire:click="playNextWeek" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="inline-block shadow-none px-8 py-3 bg-blue-400 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-500 hover:shadow-lg focus:bg-blue-500 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-600 active:shadow-lg transition duration-150 ease-in-out">
                        Play Next Week
                    </button>
                @endif

                <button wire:click="resetData" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="inline-block px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg transition duration-150 ease-in-out">
                    Reset Data
                </button>
            </div>
        </div>
    </div>

</div>