<?php

use Illuminate\Database\Seeder;
use App\Models\FrameDimension;

class FrameDimensionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $frame_dimensions = [
            //for regular framing with frame_type_id=1
            [
                'frame_type_id' => 1,
                'frame_dimension' => '8x10',
                'price' => 500000,
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '5x7',
                'price' => 800000,
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '8.5x11',
                'price' => 950000,
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '11x14',
                'price' => 1200000,
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '16x20',
                'price' => 1470000,
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '24x36',
                'price' => 2000000,
            ],


            //for illustration framing with frame_type_id=2
            [
                'frame_type_id' => 2,
                'frame_dimension' => '8x10',
                'price' => 600000,
            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '5x7',
                'price' => 900000,
            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '8.5x11',
                'price' => 1050000,
            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '11x14',
                'price' => 1400000,
            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '16x20',
                'price' => 1670000,
            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '24x36',
                'price' => 2200000,
            ],


            //for Quote framing with frame_type_id=3
            [
                'frame_type_id' => 3,
                'frame_dimension' => '8x10',
                'price' => 300000,
            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '5x7',
                'price' => 500000,
            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '8.5x11',
                'price' => 750000,
            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '11x14',
                'price' => 1000000,
            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '16x20',
                'price' => 1270000,
            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '24x36',
                'price' => 1700000,
            ],
        ];

        foreach ($frame_dimensions as $frame_dimension) {
            FrameDimension::create($frame_dimension);
        }
    }
}
