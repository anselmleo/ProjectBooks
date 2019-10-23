<?php

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $orders = [
            [
                'full_name' => 'Customer One',
                'frame_type' => 1,
                'frame_image' => 'mgi1_1571817434.png',
                'frame_dimension' => 4,
                'shipping_addr' => '7 Kola Aina Str, Ajah',
                'state' => 'Lagos'
            ],
            [
                'full_name' => 'Customer Two',
                'frame_type' => 2,
                'frame_image' => 'mgi1_1571817434.png',
                'frame_dimension' => 9,
                'shipping_addr' => '2 Jerry Iriabe, Lekki',
                'state' => 'Lagos'
            ],
            [
                'full_name' => 'Customer One',
                'frame_type' => 3,
                'frame_text' => 'Despise not the days of humble beginnings',
                'frame_dimension' => 16,
                'shipping_addr' => '25, Bishop Aboyade Cole, VI',
                'state' => 'Lagos',
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
        
    }
}
