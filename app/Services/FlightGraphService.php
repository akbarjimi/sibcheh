<?php

namespace App\Services;

class FlightGraphService
{
    private $edges;

    public function init(array $edges)
    {
        $this->edges = $edges;
    }

    public function isValidGraph(): bool
    {
        $nodes = [];

        foreach ($this->edges as $edge) {
            if (count($edge) !== 2) {
                return false;
            }

            $nodes[] = $edge[0];
            $nodes[] = $edge[1];
        }

        $uniqueNodes = array_unique($nodes);
        $countUniqueNodes = count($uniqueNodes);
        $countEdges = count($this->edges);

        return $countEdges === $countUniqueNodes - 1;
    }

    public function getOriginAndDestination(): array
    {
        if (!$this->isValidGraph()) {
            return [];
        }

        $inDegrees = [];
        $outDegrees = [];

        foreach ($this->edges as $edge) {
            $outDegrees[$edge[0]] = ($outDegrees[$edge[0]] ?? 0) + 1;
            $inDegrees[$edge[1]] = ($inDegrees[$edge[1]] ?? 0) + 1;
        }

        $origin = null;
        $destination = null;

        foreach ($outDegrees as $node => $outDegree) {
            if (!isset($inDegrees[$node])) {
                $origin = $node;
            } elseif ($outDegree < $inDegrees[$node]) {
                $origin = $node;
            }
        }

        foreach ($inDegrees as $node => $inDegree) {
            if (!isset($outDegrees[$node])) {
                $destination = $node;
            } elseif ($inDegree < $outDegrees[$node]) {
                $destination = $node;
            }
        }

        return [$origin, $destination];
    }
}
