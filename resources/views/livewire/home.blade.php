<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-16">
                <h3 class="font-medium leading-tight text-3xl mt-0 mb-2 text-gray-800 text-center">Tournament Teams</h3>
                <div class="flex justify-center">
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 max-w-2xl w-full">
                        <div class="py-4 inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="overflow-hidden">
                                <table class="min-w-full text-center">
                                    <thead class="border-b bg-gray-800">
                                    <tr>
                                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                            Team Name
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($teams as $team)
                                        <tr class="bg-white border-b">
                                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap text-left">
                                                {{$team->name}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2 justify-center">
                    <div>
                        <button wire:click="generateFixtures" type="button" class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                            Generate Fixtures
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
