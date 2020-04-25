<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ElectoralUnitsRequest;
use App\ElectoralUnit;

class ElectoralUnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ElectoralUnit::with('town')->with('settlement')->paginate(10);
    }

    public function allData()
    {
        return ElectoralUnit::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ElectoralUnitsRequest $request)
    {
        return ElectoralUnit::create([
            'town_id' => $request['town_id'],
            'settlement_id' => $request['settlement_id'],
            'name' => $request['name'],
            'expected_result' => $request['expected_result'],
            'capillary' => $request['capillary']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ElectoralUnitsRequest $request, $id)
    {
        $electoralUnit = ElectoralUnit::findOrFail($id);

        $electoralUnit->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $electoralUnit = ElectoralUnit::findOrFail($id);

        $electoralUnit->delete();
    }
}
