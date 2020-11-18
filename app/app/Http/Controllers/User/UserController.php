<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\UserEloquentRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserEloquentRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->all();
        return response()->json([
            'code' => 200,
            'data' => $users
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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:2|max:32',
            'confirmPass' => 'required|same:password|min:2|max:32'
        ]);
        $user = new User([
            'name' => $request->name,
            'level' => $request->level ? $request->level : 2,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);
        return response()->json([
            'code' => 200,
            'data' => $user
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
            $user = $this->userRepository->update($request->all(), $id);
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Update User",
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
            $user = $this->userRepository->delete($id);
            DB::commit();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Delete User",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function changePass(Request $request, $id)
    {
        $user = $this->userRepository->find($id);
        $request->validate([
            'oldPass' => 'required',
            'newPass' => 'required|different:oldPass|min:2|max:32',
            'confirmPass' => 'required|same:newPass|min:2|max:32'
        ]);
        if (Hash::check($request->oldPass, Auth::user()->password)) {
            $user->password = Hash::make($request->newPass);
            $user->save();
            return response()->json([
                'code' => 200,
                'msg' => "Successfully Update Password",
            ]);
        }
        return response()->json([
            'code' => 400,
            'msg' => 'Wrong current password, try again',
        ]);
    }
}
