<?php

namespace App\Http\Controllers\Folio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\FolioEloquentRepository;
use App\Repositories\Eloquent\CustomerEloquentRepository;
use App\Repositories\Eloquent\RoomEloquentRepository;
use App\Repositories\Eloquent\BookingEloquentRepository;

use DB;
use App\Folio;
use App\Room;
use App\Customer;
use App\Booking;

class FolioController extends Controller
{
    protected $folioRepository;
    protected $roomRepository;
    protected $bookingRepository;
    protected $customerRepository;

    public function __construct(
        FolioEloquentRepository $folioRepository,
        CustomerEloquentRepository $customerRepository,
        RoomEloquentRepository $roomRepository,
        BookingEloquentRepository $bookingRepository
    )
    {
        $this->folioRepository = $folioRepository;
        $this->customerRepository = $customerRepository;
        $this->roomRepository = $roomRepository;
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $folio = $this->folioRepository->getAllFolio();
        $arrFolio = [];
        foreach ($folio as $value) {
            $nameStatus = "";
            switch ($value->status_folio) {
                case 1:
                    $nameStatus = "Nhận phòng ";
                    break;
                case 2:
                    $nameStatus = "Trả phòng";
                    break;
                case 3:
                    $nameStatus = "Mới";
                    break;
                case 4:
                    $nameStatus = "Huỷ";
                    break;
                default:
            }
            $arrFolio[] = [
                'id' => $value->id,
                'confirm_no' => $value->confirm_no,
                'customer_name' => $value->customer_name,
                'customer_sex' => $value->customer_sex,
                'room_id' => $value->room_number,
                'room_type' => $value->room_type,
                'price_total' => number_format($value->rate_code, 2) . ' ' . 'VNĐ',
                'rate_override' => number_format($value->rate_override, 2) . ' ' . 'VNĐ',
                'price_advance' => number_format($value->price_advance, 2) . ' ' . 'VNĐ',
                'service' => $value->service,
                'date_arrival' => date('d/m/Y', strtotime($value->date_arrival)),
                'date_department' => date('d/m/Y', strtotime($value->date_department)),
                'action' => "<Link to='/chi-tiet-khach-le/$value->id'/>",
                'status' => $value->status,
                'status_folio' => $nameStatus,
            ];
        }
        return response()->json([
            'status' => 200,
            'data' => $arrFolio
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
     * @return string
     */

    public function generate_string($input, $strength = 16)
    {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    public function store(Request $request)
    {
        $permitted_chars = '0123456789';
        $confirm_no = self::generate_string($permitted_chars, 6);
        $dataFolio = [
            'confirm_no' => $confirm_no,
            'customer_name' => $request->customer_name ? $request->customer_name : null,
            'status' => $request->status,
            'date_arrival' => $request->date_arrival,
            'date_department' => $request->date_department,
            'customer_no_adults' => $request->customer_no_adults ? $request->customer_no_adults : null,
            'customer_no_young' => $request->customer_no_young ? $request->customer_no_young : null,
            'customer_no_baby' => $request->customer_no_baby ? $request->customer_no_baby : null,
            'room_id' => $request->room_id ? $request->room_id : null,
            'room_type' => $request->room_type ? $request->room_type : null,
            'rate_code' => $request->rate_code ? $request->rate_code : null,
            'rate_override' => $request->rate_override ? $request->rate_override : null,
            'discount' => $request->discount ? $request->discount : null,
            'price_total' => $request->price_total ? $request->price_total : null,
            'price_advance' => $request->price_advance ? $request->price_advance : null,
            'note' => $request->note,
            'status_folio' => Folio::CHUA_DEN,
            'num_nigth' => $request->get('num_nigth') ? $request->get('num_nigth') : null
        ];
        DB::beginTransaction();
        try {
            $folio = $this->folioRepository->create($dataFolio);
            //Create booking
            $booking = [
                'folio_id' => $folio->id,
                'confirm_id' => $confirm_no,
                'service_id' => null,
                'booking_type' => Booking::BOOKING_NEW,
                'price_total_booking' => $request->price_total,
                'booking_quality' => 1,
                'rate_code' => $request->rate_code ? $request->rate_code : null,
                'rate_discount_money' => $request->rate_override ? $request->rate_override : null,
                'discount_percent' => $request->discount ? $request->discount : null,
                'note' => $request->note ? $request->note : null];
            $this->bookingRepository->create($booking);
            //create room
            if ($request->room_id) {
                $room = $this->roomRepository->update(['room_status' => Room::PHONG_OOO], $request->room_id);
            }
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Created Folio",
                'folio_id' => $folio->id,
                'data' => $dataFolio
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $folio = $this->folioRepository->find($id);
        return response()->json([
            'code' => 200,
            'data' => $folio
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $folio = $this->folioRepository->update($request->all(), $id);
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => 'Update successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $folio = $this->folioRepository->delete($id);
            $customer = Customer::where('folio_id', '=', $id)->update(['folio_id', null]);
            DB::commit();
            return response()->json([
                'code' => 200,
                'data' => null,
                'msg' => 'Delete successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    //Update Customer to folio

    public function updateCustomerToFolio(Request $request)
    {
        DB::beginTransaction();
        try {
           $customer = $this->customerRepository->create($request->all());
            DB::commit();
            return response()->json([
                'code' => 200,
                'data' => $customer,
                'msg' => 'Add customer successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    public function search(Request $request)
    {
        if ($request->id) {
            return false;
        }

        $folio = $this->folioRepository->find($request->id);
        return response()->json([
            'code' => 200,
            'data' => $folio,
        ]);
    }

}
