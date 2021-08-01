<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Counter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3>Book Tickets for a Train Coach</h3>
                    <hr>    
                    <form class="p-10" method="POST" action="{{ route('ticket-book') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div>
                            Number of tickets Available <strong>{{$seatsAvailable}}</strong>
                        </div>
                        <br>
                        <div>
                            <x-label for="no_of_tickets" :value="__('Enter Number of tickets')" />
                            <x-input id="no_of_tickets" class="block mt-1 w-full" type="number" name="no_of_tickets" :value="old('no_of_tickets')" autofocus />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Book Tickets') }}
                            </x-button>
                        </div>
                        <a class="alert-danger" href="{{ route('delete-tickets') }}" onclick="return confirm('Are you Sure?');">Delete all Tickets</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>