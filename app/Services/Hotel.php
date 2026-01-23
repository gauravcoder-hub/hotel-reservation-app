<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Support\Collection;

class Hotel
{
    public function getRooms()
    {
        return Room::orderBy('floor')->orderBy('number')->get()->groupBy('floor');
    }

    public function bookRooms(int $requestedRooms)
    {
        $floors = Room::select('floor')->distinct()->orderBy('floor')->pluck('floor');

        foreach ($floors as $floor) {
            $available = Room::where('floor', $floor)
                ->where('is_booked', false)
                ->orderBy('number')
                ->get();

            if ($available->count() >= $requestedRooms) {
                $bestRooms = $this->pickContiguousRooms($available, $requestedRooms);
                $bestRooms->each(fn($r) => $r->update(['is_booked' => true]));
                return $bestRooms;
            }
        }

        $available = Room::where('is_booked', false)->orderBy('floor')->orderBy('number')->get();

        $bestCombination = $this->pickMinTravelRooms($available, $requestedRooms);

    
        $bestCombination->each(fn($r) => $r->update(['is_booked' => true]));

        return $bestCombination;
    }

    private function pickContiguousRooms(Collection $rooms, int $count): Collection
    {
        $best = null;
        $minDistance = PHP_INT_MAX;

        $numbers = $rooms->pluck('number')->toArray();

        for ($i = 0; $i <= count($numbers) - $count; $i++) {
            $slice = array_slice($numbers, $i, $count);
            $distance = max($slice) - min($slice); // horizontal distance
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $best = $slice;
            }
        }

        return $rooms->whereIn('number', $best);
    }

    private function pickMinTravelRooms(Collection $rooms, int $count): Collection
    {
        $bestCombination = null;
        $minTime = PHP_INT_MAX;

        $numbers = $rooms->pluck('number')->toArray();
        $roomsArray = $rooms->all();

        // Generate all combinations (n choose count)
        $combinations = $this->combinations($roomsArray, $count);

        foreach ($combinations as $combo) {
            $times = array_map(fn($r) => [$r->floor, $r->number], $combo);

            // Horizontal time = difference between min and max room number on same floor
            $floorsGrouped = [];
            foreach ($times as [$floor, $number]) {
                $floorsGrouped[$floor][] = $number;
            }

            $horizontal = 0;
            foreach ($floorsGrouped as $nums) {
                $horizontal += max($nums) - min($nums);
            }

            // Vertical time = difference between highest and lowest floor * 2
            $floors = array_column($times, 0);
            $vertical = (max($floors) - min($floors)) * 2;

            $totalTime = $horizontal + $vertical;

            if ($totalTime < $minTime) {
                $minTime = $totalTime;
                $bestCombination = $combo;
            }
        }

        return collect($bestCombination);
    }

    private function combinations(array $arr, int $count): array
    {
        if ($count === 1) {
            return array_map(fn($v) => [$v], $arr);
        }

        $result = [];
        foreach ($arr as $i => $v) {
            $rest = array_slice($arr, $i + 1);
            foreach ($this->combinations($rest, $count - 1) as $c) {
                $result[] = array_merge([$v], $c);
            }
        }
        return $result;
    }

    public function randomOccupancy()
    {
        Room::query()->update(['is_booked' => false]);
        Room::inRandomOrder()->limit(rand(10, 50))->update(['is_booked' => true]);
    }

    public function reset()
    {
        Room::query()->update(['is_booked' => false]);
    }
}
