<div class="bg-white">
    <div class="grid grid-cols-6 gap-4 mt-5 mx-4 py-8">
        <div class="overflow-x-auto max-w-2xl col-span-3">
            <div class="inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center">
                        <thead class="border-b bg-gray-800">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                Team Name
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                P
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                W
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                D
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                L
                            </th>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                GD
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
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    0
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    0
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    0
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

        <div class="overflow-x-auto max-w-2xl col-span-3">
            <div class="inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center">
                        <thead class="border-b bg-gray-800">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-white px-6 py-4">
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
                <button wire:click="playAllWeeks" type="button" class="inline-block px-8 py-3 bg-blue-400 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-500 hover:shadow-lg focus:bg-blue-500 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-600 active:shadow-lg transition duration-150 ease-in-out">
                    Play All Weeks
                </button>


                <button wire:click="playNextWeek" type="button" class="inline-block px-8 py-3 bg-blue-400 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-500 hover:shadow-lg focus:bg-blue-500 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-600 active:shadow-lg transition duration-150 ease-in-out">
                    Play Next Week
                </button>

                <button wire:click="resetData" type="button" class="inline-block px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg transition duration-150 ease-in-out">
                    Reset Data
                </button>
            </div>
        </div>
    </div>

</div>