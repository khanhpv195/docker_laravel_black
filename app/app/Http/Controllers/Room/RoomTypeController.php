<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\RoomTypeEloquentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\RoomType;

class RoomTypeController extends Controller
{
    protected $roomTypeRepository;

    public function __construct(RoomTypeEloquentRepository $roomTypeRepository)
    {
        $this->roomTypeRepository = $roomTypeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $room_type = RoomType::all();
        return response()->json([
            'code' => 200,
            'data' => $room_type
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $room_type = RoomType::create($request->all());
        return response()->json([
            'code' => 200,
            'msg' => 'create success room type'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $roomType = $this->roomTypeRepository->find($id);
        return response()->json([
            'code' => 200,
            'data' => $roomType
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $roomType = $this->roomTypeRepository->update($request->all(), $id);
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Update Type",
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $roomType = $this->roomTypeRepository->delete($id);
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Delete Room Type",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ]);
        }
    }
}
