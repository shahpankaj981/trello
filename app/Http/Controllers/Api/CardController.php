<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrelloService;
use App\Models\Column;
use App\Models\Card;

class CardController extends Controller
{
    protected $trelloService;

    public function __construct(TrelloService $trelloService)
    {
        $this->trelloService = $trelloService;
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'description', 'column_id']);
        $data['order'] = app(Card::class)->where('column_id', $request->column_id)->max('order')+1;
        app(Card::class)->create($data);

        return response()->json(['message' => 'Card created successfully!'], 200);

    }

    public function update(Request $request, $cardId)
    {
        $card = app(Card::class)->findOrFail($cardId);
        $card->update($request->only('name', 'description'));

        return response()->json(['message' => 'Card updated successfully!'], 200);
    }

    public function destroy($id)
    {
        $card = app(Card::class)->findOrFail($id);
        $card->delete();

        return response()->json(['message' => 'Card deleted successfully!'], 200);

    }
}
