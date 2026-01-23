<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Hotel;
use Illuminate\Validation\ValidationException;

class HotelController extends Controller
{
    protected Hotel $hotel;

    public function __construct(Hotel $hotel)
    {
        $this->hotel = $hotel;
    }

    public function index()
    {
        $rooms = $this->hotel->getRooms();

        return view('hotel', [
            'hotel' => $rooms
        ]);
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'rooms' => 'required|integer|min:1|max:10',
        ]);

        try {
            $rooms = $this->hotel->bookRooms((int) $validated['rooms']);

            return redirect()
                ->route('hotel.index')
                ->with('booked', $rooms->pluck('number')->toArray());

        } catch (\DomainException $e) {
            return redirect()
                ->back()
                ->withErrors($e->getMessage());

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withErrors('Booking failed. Please try again.');
        }
    }


    public function random()
    {
        $this->hotel->randomOccupancy();

        return redirect()
            ->route('hotel.index')
            ->with('success', 'Random occupancy applied!');
    }


    public function reset()
    {
        $this->hotel->reset();

        return redirect()
            ->route('hotel.index')
            ->with('success', 'All bookings reset!');
    }
}
