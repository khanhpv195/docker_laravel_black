<?php

namespace App\Http\Controllers\Room;

use App\Folio;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\RoomEloquentRepository;
use App\Repositories\Eloquent\FolioEloquentRepository;

use Illuminate\Support\Facades\DB;
use App\Room;

class RoomController extends Controller
{
    protected $roomRepository;

    public function __construct(
        RoomEloquentRepository $roomRepository,
        FolioEloquentRepository $folioRepository
    )
    {
        $this->roomRepository = $roomRepository;
        $this->folioRepository = $folioRepository;
    }

    public function index()
    {
        $room = $this->roomRepository->paginate(50);
        return response()->json($room);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $room = $this->roomRepository->create($request->all());
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Created Room",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            // throw new Exception($e->getMessage());
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
//        $room =  DB::table('rooms')
//            ->join('folios', 'rooms.id', '=', 'folios.room_id')
//            ->select('rooms.*', 'folios.*')
//            ->where('rooms.id', $id)
//            ->get();
        $room = $this->roomRepository->find($id);
        return response()->json([
            'code' => 200,
            'data' => $room
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $room = $this->roomRepository->update($request->all(), $id);
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Update Room",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            // throw new Exception($e->getMessage());
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $room = $this->roomRepository->delete($id);
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Delete Room",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function showRoomByType($id)
    {

        $room = Room::where('room_status', Room::PHONG_TRONG)->where('room_type', $id)->get();
        return response()->json([
            'code' => 200,
            'data' => $room
        ]);
    }

    public function showRoomByID()
    {

    }

}
