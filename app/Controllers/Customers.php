<?php

namespace App\Controllers;

class Customers extends BaseController
{
    public function index()
    {
        $data['customers'] = [
            [
                'name'  => 'LexaFps',
                'email' => 'lexafps@example.com',
                'phone' => '09123456789',
            ],
            [
                'name'  => 'Hitmanreborn',
                'email' => 'hitmanreborn@example.com',
                'phone' => '09187654321',
            ],
            [
                'name'  => 'Pedro Omsim',
                'email' => 'pedro@example.com',
                'phone' => '09112223344',
            ],
            [
                'name'  => 'Ada Wong',
                'email' => 'ada@example.com',
                'phone' => '09998887766',
            ],
            [
                'name'  => 'Zyrah',
                'email' => 'zyrah@example.com',
                'phone' => '09001112233',
            ],
        ];

        return view('customers/index', $data);
    }
}