<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 font-sans min-h-screen">

<div class="container mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">
        Hotel Room Reservation System
    </h1>
    <div class="max-w-4xl mx-auto mb-10 p-6 bg-white rounded-2xl shadow-lg border border-gray-200 flex flex-col gap-4">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">Book Your Rooms</h2>
            <p class="text-sm text-gray-500">Select number of rooms and perform actions</p>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <form action="{{ route('book.rooms') }}" method="POST"
                  class="flex items-center gap-3 flex-1 min-w-[260px]">
                @csrf

                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                    <button type="button"
                        onclick="changeRooms(-1)"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition text-lg font-bold">
                        −
                    </button>

                    <input
                        id="roomsInput"
                        type="number"
                        name="rooms"
                        value="1"
                        min="1"
                        max="5"
                        readonly
                        class="w-16 text-center border-l border-r border-gray-300
                               focus:outline-none bg-white text-gray-800 font-semibold"
                    />

                    <button type="button"
                        onclick="changeRooms(1)"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition text-lg font-bold">
                        +
                    </button>
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg
                           font-medium shadow-sm transition">
                    Book Rooms
                </button>
            </form>
            <form action="{{ route('hotel.random') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg
                           font-medium shadow-sm transition">
                    Random
                </button>
            </form>
            <form action="{{ route('hotel.reset') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg
                           font-medium shadow-sm transition">
                    Reset
                </button>
            </form>
        </div>
        @if(session('booked'))
            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700 font-semibold">
                    Booked Rooms: {{ implode(', ', session('booked')) }}
                </p>
            </div>
        @endif
    </div>

    <div class="grid gap-6 max-w-6xl mx-auto">
        @foreach($hotel as $floor => $floorRooms)
            <div>
                <h2 class="font-bold mb-2 text-gray-700">Floor {{ $floor }}</h2>

                <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-2">
                    @foreach($floorRooms as $room)
                        <div
                            class="text-center py-2 rounded-lg border cursor-pointer
                            transition-all duration-200 ease-in-out
                            {{ $room->is_booked
                                ? 'bg-red-500 text-white scale-105 shadow-md'
                                : 'bg-green-200 hover:bg-green-300 hover:scale-105' }}">
                            {{ $room->number }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    function changeRooms(step) {
        const input = document.getElementById('roomsInput');
        let value = parseInt(input.value);

        const min = 1;
        const max = 5;

        value += step;

        if (value < min) value = min;
        if (value > max) value = max;

        input.value = value;
    }
</script>
@if(session('booked'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Booked Rooms: {{ implode(", ", session("booked")) }}',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
</script>
@endif

</body>
</html>
