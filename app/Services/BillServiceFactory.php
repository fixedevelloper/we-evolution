<?php


namespace App\Services;


class BillServiceFactory
{
    public static function make($type)
    {
        return match ($type) {
        'electricity' => new ElectricityBillService(),
            'water' => new WaterBillService(),
            'canal' => new CanalBillService(),
            default => new GenericBillService(),
        };
    }
}

