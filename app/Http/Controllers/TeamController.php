<?php

namespace App\Http\Controllers;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    protected $request;

    public function __construct(Request $request, Team $team)
    {
        $this->$request = $request;
        $this->repositoryTeam = $team;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = DB::table('teams')->get();
        return $teams;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->repositoryTeam->name=$request->name;
        $this->repositoryTeam->save();
        return response()->json([
            "message" => "Time Criado com sucesso"
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ['teams' => Team::findOrFail($id)];
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $team = Team::findOrFail($id);
        $team->update($inputs);
        return response()->json([
            "message" => "Time Alterado com sucesso"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return response()->json([
            "message" => "Time Deletado com sucesso"
        ], 201);
    }
}
