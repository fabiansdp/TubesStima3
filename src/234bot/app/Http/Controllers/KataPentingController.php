<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KataPenting;

class KataPentingController extends Controller
{
    function getData(){
        return KataPenting::all();
    }

    function addData(Request $req){
        $kataPenting = new KataPenting;
        $kataPenting->kata_penting=$req->value;
        $kataPenting->save();
        return redirect('penting');
    }
}
