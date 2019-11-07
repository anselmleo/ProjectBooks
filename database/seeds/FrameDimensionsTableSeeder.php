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
                'label' => '8x10(₦5,000)'
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '5x7',
                'price' => 800000,
                'label' => '5x7(₦8,000)'
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '8.5x11',
                'price' => 950000,
                'label' => '8.5x11(₦9,500)'
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '11x14',
                'price' => 1200000,
                'label' => '11x14(₦12,000)'
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '16x20',
                'price' => 1470000,
                'label' => '16x20(₦14,700)'
            ],
            [
                'frame_type_id' => 1,
                'frame_dimension' => '24x36',
                'price' => 2000000,
                'label' => '24x36(₦20,000)'
            ],


            //for illustration framing with frame_type_id=2
            [
                'frame_type_id' => 2,
                'frame_dimension' => '8x10',
                'price' => 600000,
                'label' => '8x10(₦6,000)'

            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '5x7',
                'price' => 900000,
                'label' => '5x7(₦9,000)'

            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '8.5x11',
                'price' => 1050000,
                'label' => '8.5x11(₦10,500)'

            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '11x14',
                'price' => 1400000,
                'label' => '11x14(₦14,000)'

            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '16x20',
                'price' => 1670000,
                'label' => '16x20(₦16,700)'

            ],
            [
                'frame_type_id' => 2,
                'frame_dimension' => '24x36',
                'price' => 2200000,
                'label' => '24x36(₦22,000)'

            ],


            //for Quote framing with frame_type_id=3
            [
                'frame_type_id' => 3,
                'frame_dimension' => '8x10',
                'price' => 300000,
                'label' => '8x10(₦3,000)'

            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '5x7',
                'price' => 500000,
                'label' => '5x7(₦5,000)'

            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '8.5x11',
                'price' => 750000,
                'label' => '8.5x11(₦7,500)'

            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '11x14',
                'price' => 1000000,
                'label' => '11x14(₦10,000)'

            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '16x20',
                'price' => 1270000,
                'label' => '16x20(₦12,700)'

            ],
            [
                'frame_type_id' => 3,
                'frame_dimension' => '24x36',
                'price' => 1700000,
                'label' => '24x36(₦17,000)'

            ],
        ];

        foreach ($frame_dimensions as $frame_dimension) {
            FrameDimension::create($frame_dimension);
        }
    }
}
