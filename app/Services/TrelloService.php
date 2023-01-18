<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrelloService;
use App\Models\Column;
use App\Models\Card;
use Illuminate\Support\Facades\DB;


class TrelloService
{
    public function getData($filters = [])
    {
        return app(Column::class)->with(['cards' => function($q) {
            return $q->orderBy('order')->orderBy('updated_at', 'desc');
        }])->orderBy('order')->get();
    }

    public function addColumn($data)
    {
        $maxOrder = app(Column::class)->max('order');
        $data['order'] = $maxOrder + 1;
        return app(Column::class)->create($data);
    }

    public function reorderCards($cardId, $updateData)
    {
        $card = app(Card::class)->find($cardId);
        if ($updateData['from_column_id'] == $updateData['to_column_id']) {
            $movement = $updateData['new_order'] > $updateData['old_order'] ? 'down'
                : ($updateData['new_order'] < $updateData['old_order'] ? 'up' : '');
            if ($movement == 'down') {
                DB::statement(sprintf("update cards set `order`=`order`-1 where `column_id`=%s and `order` >= %s and `order` <= %s", $card->column_id, $updateData['old_order'] + 1, $updateData['new_order']));
                $card->update(['order' => $updateData['new_order']]);
            } elseif ($movement == 'up') {
                DB::statement(sprintf("update cards set `order`=`order`+1 where `column_id`=%s and `order` >= %s and `order` <= %s", $card->column_id, $updateData['new_order'], $updateData['old_order'] - 1));
                $card->update(['order' => $updateData['new_order']]);
            }
        } else {
            DB::statement(sprintf("update cards set `order`=`order`-1 where `column_id`=%s and `order` >= %s", $updateData['from_column_id'], $updateData['old_order'] + 1));
            DB::statement(sprintf("update cards set `order`=`order`+1 where `column_id`=%s and `order` >= %s", $updateData['to_column_id'], $updateData['new_order']));

            $card->update(['order' => $updateData['new_order'], 'column_id' => $updateData['to_column_id']]);
            
        }
    }
}