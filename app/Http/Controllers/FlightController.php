<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightRequest;
use App\Models\Location;
use App\Models\User;
use App\Services\FlightGraphService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    public function store(StoreFlightRequest $request)
    {
        $passport = $request->post('passport');
        $flightPaths = $request->post('path');

        DB::beginTransaction();

        try {
            /** @var User $user */
            $user = User::wherePassport($passport)->first();

            foreach ($flightPaths as $path) {
                $fromLocation = Location::whereName($path['from'])->first();
                $toLocation = Location::whereName($path['to'])->first();

                $user->flights()->create([
                    'from_location_id' => $fromLocation->id,
                    'to_location_id' => $toLocation->id,
                    'departure_time' => now(),
                    'arrival_time' => now()->addHours(2),
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'User and flight paths saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['error' => 'Error saving user and flight paths.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function track(FlightGraphService $flightGraphService, string $passport)
    {
        $user = User::wherePassport($passport)->first();

        $flightPaths = $user->flights()->with(['origin:id,name', 'destination:id,name'])->get();;

        if ($flightPaths->isEmpty()) {
            return response()->json(['error' => 'User has no recorded flight paths.'], Response::HTTP_NOT_FOUND);
        }

        $edges = [];
        foreach ($flightPaths as $flightPath) {
            $edges[] = [
                $flightPath->origin->name,
                $flightPath->destination->name,
            ];
        }

        $flightGraphService->init($edges);
        if ($flightGraphService->isValidGraph()) {
            list($origin, $destination) = $flightGraphService->getOriginAndDestination();
            return response()->json([
                'origin' => $origin,
                'destination' => $destination
            ]);
        } else {
            return response()->json(['error' => 'Not a valid flight path.'], Response::HTTP_NOT_FOUND);
        }
    }
}
