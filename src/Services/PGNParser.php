<?php
// src/Services/PGNParser.php

namespace Chesskeeper\Services;

class PGNParser
{
    public function parse(string $rawInput): array
    {
        $games = preg_split("/\n\s*\n(?=\[Event )/", trim($rawInput));
        $result = [];

        foreach ($games as $gameText) {
            $tags = [];
            preg_match_all('/\[(\w+)\s+\"([^\"]*)\"\]/', $gameText, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $tags[$match[1]] = $match[2];
            }

            $moveSection = preg_replace('/\[.*?\]/s', '', $gameText); // Tags entfernen
            $moveSection = preg_replace('/\{.*?\}/s', '', $moveSection); // Kommentare entfernen
            $moveSection = preg_replace('/\([^)]*\)/s', '', $moveSection); // Varianten entfernen
            // Zugnummern bleiben erhalten für Lesbarkeit und PGN-Rekonstruktion
            //$moveSection = preg_replace('/\d+\.(\.\.\.)?/', '', $moveSection); // Zugnummern entfernen
            $moveSection = trim(preg_replace('/\s+/', ' ', $moveSection)); // Whitespace normalisieren

            $result[] = [
                'white' => $tags['White'] ?? 'Unknown',
                'black' => $tags['Black'] ?? 'Unknown',
                'event' => $tags['Event'] ?? null,
                'site' => $tags['Site'] ?? null,
                'date' => $tags['Date'] ?? null,
                'round' => $tags['Round'] ?? null,
                'result' => match($tags['Result'] ?? '*') {
                    '1-0' => 1,
                    '0-1' => -1,
                    '1/2-1/2' => 0.5,
                    default => 0
                },
                'pgn' => $gameText,
                'moves' => $moveSection
            ];
        }

        return $result;
    }
}
