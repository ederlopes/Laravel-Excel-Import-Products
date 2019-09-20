<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProductImport implements WithHeadingRow, ToModel, WithChunkReading, ShouldQueue
{

    public function model(array $row)
    {
        return new Product([
            'im'            => $row['im'],
            'name'          => $row['name'],
            'free_shipping' => $row['free_shipping'],
            'description'   => $row['description'],
            'price'         => $row['price'],

        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

}
