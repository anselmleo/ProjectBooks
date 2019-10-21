<?php

use Illuminate\Database\Seeder;
use App\Models\FrameType;

class FrameTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $frame_types = [
            "Regular", "Illustration", "Quotes"
        ];

        foreach ($frame_types as $frame_type) {
            FrameType::create([
                'frame_type' => $frame_type
            ]);
        }
    }
}
