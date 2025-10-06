<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class MoneyInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (MoneyInput $component, $state) {
            if ($state !== null) {
                $component->state(number_format($state, 0, ',', '.'));
            }
        });

        $this->dehydrateStateUsing(function ($state) {
            if ($state === null) {
                return 0;
            }
            return (int) Str::of($state)->replace(['.', ','], '')->toString();
        });

        $this->formatStateUsing(function ($state) {
            if ($state === null) {
                return '0';
            }
            return number_format($state, 0, ',', '.');
        });

        $this->live()
            ->debounce(500)
            ->afterStateUpdated(function ($state, $set) {
                if ($state === null) {
                    $set('0');
                    return;
                }
                $set(number_format((int) Str::of($state)->replace(['.', ','], '')->toString(), 0, ',', '.'));
            });
    }
} 