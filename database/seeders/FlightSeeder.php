<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User $user */
        $locations = Location::all();
        $max = $locations->count();
        foreach (User::all() as $user) {
            $nodes = $locations->random(random_int(2, 4))->pluck('id')->toArray();
            $graph = $this->buildConnectedGraph($nodes);
            foreach ($graph as $nodes) {
                $user->flights()->create(Flight::factory()->makeOne([
                    'from_location_id' => $nodes[0],
                    'to_location_id' => $nodes[1],
                    'user_id' => $user->id,
                ])->toArray());
            }
        }
    }


    public function buildConnectedGraph($nodes): array
    {
        $edgePairs = array();

        $numNodes = count($nodes);
        for ($i = 0; $i < $numNodes - 1; $i++) {
            $node1 = $nodes[$i];
            $node2 = $nodes[$i + 1];

            $edgePairs[] = [$node1, $node2];
        }

        return $edgePairs;
    }
}
