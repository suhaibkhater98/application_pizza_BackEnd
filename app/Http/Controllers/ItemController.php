<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResource;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function get_items()
    {
        $item = DB::table('items')->get();
        return response()->json($item);
    }
}
