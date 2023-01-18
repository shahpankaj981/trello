<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrelloService;
use App\Models\Column;
use App\Models\Card;
use Spatie\DbDumper\Databases\MySql;

class TrelloController extends Controller
{
    protected $trelloService;

    public function __construct(TrelloService $trelloService)
    {
        $this->trelloService = $trelloService;
    }

    public function getData(Request $request)
    {
        $columns = $this->trelloService->getData($request);
        
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

    public function dumpSql(Request $request)
    {
        $pathToFile = base_path('dump.sql');
        
        // MySql::create()
        //     ->setDumpBinaryPath('C:\wamp64\bin\mysql\mysql5.7.36\bin')
        //     ->setDbName(env("DB_DATABASE"))
        //     ->setUserName(env("DB_USERNAME"))
        //     ->setPassword(env("DB_PASSWORD"))
        //     ->setHost(env("DB_HOST"))
        //     ->setPort(env("DB_PORT"))
        //     ->dumpToFile($pathToFile);

        shell_exec(" C:\wamp64\bin\mysql\mysql5.7.36\bin\mysqldump -h localhost -u root trello > {$pathToFile}");

        return response()->download($pathToFile);
    }
}
