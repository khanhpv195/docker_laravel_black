<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\CustomerEloquentRepository;
use App\Repositories\Eloquent\FolioEloquentRepository;
use App\Repositories\Eloquent\ServiceEloquentRepository;
use App\Repositories\Eloquent\BookingEloquentRepository;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomerController extends Controller
{

    protected $customerRepository;
    protected $folioRepository;
    protected $serviceRepository;
    protected $bookingRepository;

    public function __construct(
        CustomerEloquentRepository $customerRepository,
        FolioEloquentRepository $folioRepository,
        ServiceEloquentRepository $serviceRepository,
        BookingEloquentRepository $bookingRepository
    )
    {
        $this->customerRepository = $customerRepository;
        $this->folioRepository = $folioRepository;
        $this->serviceRepository = $serviceRepository;
        $this->bookingRepository = $bookingRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $customer = $this->customerRepository->all();
        $arrCustomer = [];
        foreach ($customer as $value){
            $arrCustomer[] = [
                'value'=> $value->id,
                'label'=> $value->full_name
            ];
        }
        return response()->json([
            'code' => 200,
            'data' => $arrCustomer
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer = $this->customerRepository->create($request->all());
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Created customer",
            ]);
        }catch (\Exception $e) {
            DB::rollback();
            // throw new Exception($e->getMessage());
            return response()->json([
                'code' => 400,
                'msg' => "Created customer error",
            ]);
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
        $customer = $this->customerRepository->find($id);
        return response()->json([
            'code' => 200,
            'data' => $customer
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = $this->customerRepository->update($request->all(),$id);
        return response()->json([
            'code' => 200,
            'msg' => "Successfully update customer",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = $this->customerRepository->delete($id);
        return response()->json([
            'code' => 200,
            'msg' => "Successfully delete customer",
        ]);
    }

    public function searchByFolio($id)
    {
        $booking = $this->bookingRepository->findByField('folio_id',$id);
        $customer = $this->customerRepository->findByField('folio_id',$id);
        $folio = $this->folioRepository->find($id);
        $service = [];
        $dataService = [];
        foreach($service as $value){
            $dataService[] = $value->value;
        }
        $arrService = $this->serviceRepository->findWhereIn('id',$dataService);
        return response()->json([
            'code' => 200,
            'customer' => $customer,
            'folio' => $folio,
            'service'=> $arrService,
            'booking' => $booking
        ]);
    }

//    public function searchByName(Request $request)
//    {
//        $customer = Customer::where('full_name', 'LIKE', '%' . $request->keyword . '%')->get();
//        return response()->json([
//            'code' => 200,
//            'data' => $customer
//        ]);
//    }

    public function searchByName(Request $request)
    {
        if ($request) {
            if ($request->keyword) {
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('customers.full_name', 'LIKE', '%' . $request->keyword . '%')
                    ->orWhere('folios.id', 'LIKE', '%' . $request->keyword . '%')
                    ->orWhere('folios.confirm_no', 'LIKE', '%' . $request->keyword . '%')
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer
                ]);
            }
            if ($request->date_arrival) {
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.date_arrival', 'LIKE', '%' . $request->date_arrival . '%')
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer
                ]);
            }
            if ($request->date_department) {
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.date_department', 'LIKE', '%' . $request->date_department . '%')
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer
                ]);
            }
            if ($request->date_arrival && $request->date_department) {
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.date_arrival', 'BETWEEN', $request->date_arrival and $request->date_department)
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer
                ]);
            }
            if ($request->allCheckout && $request->inHouse &&
                $request->arrivingToday && $request->departureToday &&
                $request->reserved && $request->canceled) {
                $today = strtotime(Date::now());
                $today_format = \date('Y-m-d', $today);
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.status_folio', '=', Folio::CHECK_IN)
                    ->orWhere('folios.status_folio', '=', Folio::CHECK_OUT)
                    ->orWhere('folios.status_folio', '=', Folio::CHUA_DEN)
                    ->orWhere('folios.status_folio', '=', Folio::CANCELED)
                    ->orWhere('folios.date_arrival', 'LIKE', '%' . $today_format . '%')
                    ->orWhere('folios.date_department', 'LIKE', '%' . $today_format . '%')
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer
                ]);
            }
            if ($request->allCheckout) {
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.status_folio', '=', Folio::CHECK_OUT)
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer
                ]);
            }
            if ($request->inHouse) {
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.status_folio', '=', Folio::CHECK_IN)
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer
                ]);
            }
            if ($request->arrivingToday) {
                $today = strtotime(Date::now());
                $today_format = \date('Y-m-d', $today);
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.date_arrival', 'LIKE', '%' . $today_format . '%')
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer,
                ]);
            }
            if ($request->departureToday) {
                $today = strtotime(Date::now());
                $today_format = \date('Y-m-d', $today);
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.date_department', 'LIKE', '%' . $today_format . '%')
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer,
                ]);
            }
            if ($request->reserved) {
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.status_folio', '=', Folio::CHUA_DEN)
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer,
                ]);
            }
            if ($request->canceled) {
                $customer = DB::table('customers')
                    ->join('folios', 'customers.id', '=', 'folios.customer_id')
                    ->join('rooms', 'folios.room_id', '=', 'rooms.id')
                    ->select('customers.*', 'folios.*', 'rooms.name as room_name')
                    ->where('folios.status_folio', '=', Folio::CANCELED)
                    ->get();
                return response()->json([
                    'code' => 200,
                    'data' => $customer
                ]);
            }
        }
        return null;
    }
}
