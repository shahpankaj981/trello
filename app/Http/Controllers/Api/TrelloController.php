<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrelloService;
use App\Models\Column;
use App\Models\Card;

class TrelloController extends Controller
{
    protected $trelloService;

    public function __construct(TrelloService $trelloService)
    {
        $this->trelloService = $trelloService;
    }

    public function getData()
    {
        $columns = $this->trelloService->getData();
        
        return response()->json(['columns' => $columns], 200);
    }

    public function addColumn(Request $request)
    {
        $columns = $this->trelloService->addColumn($request->only('name'));
        
        return response()->json(['columns' => $columns], 200);
    }

    public function deleteColumn(Request $request, $id)
    {
        $column = app(Column::class)->find($id);
        $column->cards()->delete();
        $column->delete($id);
        
        return response()->json(['message' => 'Column Deleted successfully!'], 200);
    }

    public function getCards(Request $request, $id)
    {
        $column = app(Column::class)->find($id);

        return response()->json(['cards' => $column->cards], 200);
    }
    
    public function reorderCards(Request $request, $cardId)
    {
        $this->trelloService->reorderCards($cardId, $request->all());

        return response()->json(['message' => 'Cards reordered successfully!!'], 200);

    }
}
