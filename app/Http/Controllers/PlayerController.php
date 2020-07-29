<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Team;

class PlayerController extends Controller
{
    protected $request;

    public function __construct(Request $request, Player $player, Team $team)
    {
        $this->$request = $request;
        $this->repositoryPlayer = $player;
        $this->repositoryTeam = $team;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $players = DB::table('players')->get();
         return $players;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->savePlayer($request->name,$request->power);

        $data = $this->getRefreshTable();
        if ($data['player_totals']%2) {
            $this->ordernedTeams();
        }
        $data = $this->getRefreshTable();

        return $this->prepareReturn($data);
    }

    public function prepareReturn ($data) {
        return response()->json([
            "Quantidade de Jogadores Time 1" => count($data['team_one']),
            "Quantidade de Jogadores Time 2" => count($data['team_two']),
            "message" => "Player Criado com sucesso",
            "teams" => [
                "team_1" => $data['team_one'],
                "team_2" => $data['team_two'],
            ]
        ], 201);
    }

    public function totalsTeam ($teamOne, $teamTwo) {
        $teamOneTotals = 0;
        $teamOneArr = json_decode($teamOne, true);
        foreach ($teamOneArr as $teamOnePower) {
            $teamOneTotals += $teamOnePower['power'];
        }

        $teamTwoTotals = 0;
        $teamTwoArr = json_decode($teamTwo, true);
        foreach ($teamTwoArr as $teamTwoPower) {
            $teamTwoTotals += $teamTwoPower['power'];
        }

        return [
            "teamone" => $teamOneTotals,
            "teamtwo" => $teamTwoTotals
        ];
    }

    public function savePlayer ($name, $power) {
        $teamOne = DB::table('players')->select('power')->where('team_id', 1)->get();
        $teamTwo = DB::table('players')->select('power')->where('team_id', 2)->get();
        $teamTotals = $this->totalsTeam($teamOne, $teamTwo);

        $this->repositoryPlayer->name=$name;
        $this->repositoryPlayer->power=$power;

        if ($teamTotals['teamtwo'] >= $teamTotals['teamone']) {
            $this->repositoryPlayer->team_id = 1;
        } else {
            $this->repositoryPlayer->team_id = 2;
        }
        $this->repositoryPlayer->save();
    }

    public function getRefreshTable () {
        $teamOneRefresh = DB::table('players')->where('team_id', 1)->get();
        $teamTwoRefresh = DB::table('players')->where('team_id', 2)->get();
        $playerTotals = count($teamOneRefresh) + count($teamTwoRefresh);
        return [
            "team_one" => $teamOneRefresh,
            "team_two" => $teamTwoRefresh,
            "player_totals" => $playerTotals
        ];
    }

    public function ordernedTeams () {
        $teamOneRefresh = DB::table('players')->select('power')->where('team_id', 1)->get();
        $teamTwoRefresh = DB::table('players')->select('power')->where('team_id', 2)->get();
        $playerTotals = count($teamOneRefresh) + count($teamTwoRefresh);

        $allPlayersDesc = DB::table('players')->orderBy('power', 'DESC')->get();
        $teamOneFlag = true;
        $half = $playerTotals / 2;
        $playerAtualDesc = $playerTotals;

        foreach ($allPlayersDesc as $players) {
            if ($playerAtualDesc >= $half) {
                if ($teamOneFlag) {
                    DB::table('players')->where('id', $players->id)->update(['team_id' => 1]);
                    $teamOneFlag = false;
                } else {
                    DB::table('players')->where('id', $players->id)->update(['team_id' => 2]);
                    $teamOneFlag = true;
                }
                $playerAtualDesc--;
            } else {
                if ($teamOneFlag) {
                    DB::table('players')->where('id', $players->id)->update(['team_id' => 2]);
                    $teamOneFlag = false;
                } else {
                    DB::table('players')->where('id', $players->id)->update(['team_id' => 1]);
                    $teamOneFlag = true;
                }
                $playerAtualDesc--;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ['players' => Player::findOrFail($id)];
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
        $player = Player::findOrFail($id);
        $player->update($inputs);
        return $inputs;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();
        return response()->json([
            "message" => "Player Deletado com sucesso"
        ], 201);
    }
}
