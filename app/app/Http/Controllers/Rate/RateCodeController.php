<?php

namespace App\Http\Controllers\Rate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RateCode;
use App\Repositories\Eloquent\RateCodeEloquentRepository;

class RateCodeController extends Controller
{

    protected $rateCodeRepository;

    public function __construct(
        RateCodeEloquentRepository $rateCodeRepository
    )
    {
        $this->rateCodeRepository = $rateCodeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->type;
        $rate_code = RateCode::where('type', $type)->get();
        return response()->json([
            'code' => 200,
            'data' => $rate_code
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
        $rate_code = $this->rateCodeRepository->create($request->all());
        return response()->json([
            'code' => 200,
            'msg' => 'create success rate code'
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
