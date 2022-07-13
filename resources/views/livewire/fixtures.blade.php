<div>
    <div class="grid grid-cols-4 gap-4 mt-5 mx-12">

        @if($fixtures->count())
            @foreach($fixtures as $key => $weekFixture)
                    <div class="rounded-lg shadow-lg bg-white">
                        <h5 class="text-xl font-medium mb-2 border-b bg-gray-800 p-2 text-white">Week {{ $key + 1}}</h5>
                        <div class="p-6">
                            @foreach($weekFixture as $fixture)
                                <p class="text-gray-700 text-base mb-4">
                                    {{$fixture['home_team_name']}} - {{$fixture['away_team_name']}}
                                </p>
                            @endforeach
                        </div>
                    </div>
            @endforeach
        @else
            <div class="rounded-lg shadow-lg bg-white col-span-4 py-8">
                <div class="p-6">
                    Simulation has already been started
                </div>
            </div>
        @endif
    </div>
    <div class="flex space-x-2 mx-12 mt-12">
        <button wire:click="startSimulation" wire:loading.attr="disabled" wire:loading.class="opacity-60" type="button" class="inline-block px-32 py-6 bg-blue-400 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-500 hover:shadow-lg focus:bg-blue-500 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-600 active:shadow-lg transition duration-150 ease-in-out">
            @if($fixtures->count())
                Start Simulation
            @else
                Continue
            @endif
        </button>
    </div>
</div>
