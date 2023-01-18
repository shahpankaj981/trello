<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrelloService;
use App\Models\Column;

class TrelloController extends Controller
{
    protected $trelloService;

    public function __construct(TrelloService $trelloService)
    {
        $this->trelloService = $trelloService;
    }

    public function getColumns()
    {
        $columns = $this->trelloService->getColumns();
        
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
        $column->delete($id);
        
        return response()->json(['message' => 'Column Deleted successfully!'], 200);
    }
}
