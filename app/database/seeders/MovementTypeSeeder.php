<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $movementTypes = [
            ['code' => 'IN_PURCHASE', 'direction' => 'IN', 'affects_cost' => true],
            ['code' => 'OUT_CONSUMPTION', 'direction' => 'OUT', 'affects_cost' => false],
            ['code' => 'ADJUSTMENT_IN', 'direction' => 'IN', 'affects_cost' => false],
            ['code' => 'ADJUSTMENT_OUT', 'direction' => 'OUT', 'affects_cost' => false],
            ['code' => 'TRANSFER_OUT', 'direction' => 'OUT', 'affects_cost' => false],
            ['code' => 'TRANSFER_IN', 'direction' => 'IN', 'affects_cost' => false],
        ];

        $records = array_map(function (array $movementType) use ($now): array {
            return [
                ...$movementType,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $movementTypes);

        DB::table('movement_types')->upsert(
            $records,
            ['code'],
            ['direction', 'affects_cost', 'updated_at']
        );
    }
}
