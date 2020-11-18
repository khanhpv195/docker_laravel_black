<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\ServiceEloquentRepository;
use Illuminate\Http\Request;
use App\Service;

class ServiceController extends Controller
{
    protected $serviceRepository;

    public function __construct(
        ServiceEloquentRepository $serviceRepository
    )
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $service = $this->serviceRepository->all();
        $arrService = [];
        foreach ($service as $value){
            $arrService[] = [
                'value'=> $value->id,
                'label'=> $value->name
            ];
        }
        return response()->json([
            'code' => 200,
            'data' => $arrService,
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $service = $this->serviceRepository->create($request->all());
        return response()->json([
            'code' => 200,
            'data' => "Create success",
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
        $service = $this->serviceRepository->find($id);
        return response()->json([
            'code' => 200,
            'data' => $service,
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
        $service = $this->serviceRepository->update([$request->all(),$id]);
        return response()->json([
            'code' => 200,
            'data' => "update success",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = $this->serviceRepository->delete($id);
        return response()->json([
            'code' => 200,
            'data' => "delete success",
        ]);
    }
}
