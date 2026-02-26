<?php

namespace App\Enums;

enum ScoringMode: string
{
    case Blind = 'blind';
    case Open = 'open';

    public function label(): string
    {
        return match ($this) {
            self::Blind => 'Blind (scores hidden from other judges)',
            self::Open => 'Open (all scores visible)',
        };
    }
}
