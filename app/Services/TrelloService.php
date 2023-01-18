<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrelloService;
use App\Models\Column;

class TrelloService
{
    public function getColumns($filters = [])
    {
        return app(Column::class)->orderBy('order')->get();
    }

    public function addColumn($data)
    {
        $maxOrder = app(Column::class)->max('order');
        $data['order'] = $maxOrder + 1;
        return app(Column::class)->create($data);
    }
}