<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Counter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 items-center">
                    <h3>Thank you for booking with us.</h3>
                    <div>Booked tickets @php echo implode(',',$seats) @endphp</div>
                    <a class="green" href="{{ route('ticket-book') }}" >Book Again</a>
                </div>
                <div class="ml-50">
                    @php $seatNumber = 1 @endphp
                    @for($i=1; $i <= $rows; $i++)
                        @for($j=1; $j <= $columns; $j++)
                            @if($i == 12 && $j > 3)
                                @break;
                            @endif
                            @if(!in_array($seatNumber,$allTickets))
                                <div class="square ml-5 mb-5 float-left">{{ $seatNumber }}</div>
                            @else   
                                <div class="square-green ml-5 mb-5 float-left">{{ $seatNumber }}</div>
                            @endif
                            @php $seatNumber++ @endphp
                        @endfor
                        <div class="clear-both"></div>
                        
                    @endfor
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>