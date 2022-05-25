<?php

namespace App\Http\Controllers\V1\Auth;

use App\Models\User;
use App\Events\NewUserAdded;
use App\Jobs\SendInviteLinkJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['show']);
    }


    public function index()
    {
        return UserResource::collection(User::paginate());
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
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        //$token = $user->createToken($user->email);
        //SendInviteLinkJob::dispatch($user)->onQueue('emails');
        event(new NewUserAdded($user));

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //if (auth()->user()->id === $user->id) {

            $data = [
                "name" => $request->all()['name'],
                "email" => $request->all()['email']
            ];

            if (isset($request->all()['password'])) {

                if (Hash::check($request->all()['old_password'], auth()->user()->password)) {
                    $data['password'] = Hash::make($request->all()['password']);
                }else{
                    return response()->json([
                        "errors" => [
                           "password" => "old password does not matched"
                        ]
                    ]);
                }
            }

            $user->update($data);


            return new UserResource($user);

        //}

        //return response(status:401);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(status:201);
    }
}
