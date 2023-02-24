<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="usersGet",
     *      tags={"Users"},
     *      summary="get users list",
     *      description="get list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Employees was taken successfully"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="data", type="object"),
     *        ),
     *     ),
     *     @OA\Parameter(
     *          name="count_pg",
     *          description="paginationlar soni",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     )
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $count_pagination = \request('count_pg');
        if(empty($count_pagination)){
            $count_pagination = 10;
        }
        $users = Paginator::advansedPaginate(User::query()->where('id',3)->with('position:id,name')->paginate($count_pagination));
        unset($users->first_page_url);
            return response()->json([
            'message' => 'Employees was taken successfully',
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create()
//    {
//
//    }

    /**
     * @OA\Post(
     * path="/users",
     * summary="Store users",
     * description="create new users",
     * operationId="usersPost",
     * tags={"Users"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new users",
     *    @OA\JsonContent(
     *       required={"first_name","last_name","login","password","phone","position_id","password_confirmation"},
     *       @OA\Property(property="first_name", type="string", example="default"),
     *       @OA\Property(property="last_name", type="string", example="default"),
     *       @OA\Property(property="login", type="string", example="default"),
     *       @OA\Property(property="password", type="string", format="password"),
     *       @OA\Property(property="password_confirmation", type="string", format="password"),
     *       @OA\Property(property="phone", type="integer", example="998917777777"),
     *       @OA\Property(property="position_id", type="enum", example="4"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successful operation",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Employees data saved successfully!!!"),
     *       @OA\Property(property="status", type="boolean"),
     *        ),
     *     ),
     * ),
     */
    public function store(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        try{
            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            User::query()->create($data);
            return response()->json(['status' => true, 'message' => 'Employees data saved successfully!!!']);
        } catch (ValidationException $e) {
            return response()->json(array_values($e->errors()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
//    public function show(int $id): \Illuminate\Http\JsonResponse
//    {
//        $user = User::query()->findOrFail($id);
//        if ($user) {
//            return response()->json([
//                "message" => "Data are taken succesfully",
//                "success" => true,
//                "data" => $user
//            ]);
//        }
//
//        return response()->json([
//            'message' => 'Something wrong',
//            'success' => false,
//        ]);
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *      path="/users/{id}/edit",
     *      operationId="usersEdit",
     *      tags={"Users"},
     *      description="get a user data",
     *     @OA\Parameter(
     *          name="id",
     *          description="Kerak bo'lgan user id si",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Employee was taken successfully"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="data", type="object"),
     *        ),
     *     )
     *     )
     */
    public function edit(int $id): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->findOrFail($id);
        return response()->json([
            'message' => 'Employee was taken successfully',
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update existing user",
     *      description="Returns updated user data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="update the user",
     *          @OA\JsonContent(
     *          required={"first_name","last_name","login","password","phone","position_id","password_confirmation"},
     *          @OA\Property(property="first_name", type="string", example="default"),
     *          @OA\Property(property="last_name", type="string", example="default"),
     *          @OA\Property(property="login", type="string",example="default"),
     *          @OA\Property(property="password", type="string", format="password"),
     *          @OA\Property(property="password_confirmation", type="string", format="password"),
     *          @OA\Property(property="phone", type="integer", example="998917777777"),
     *          @OA\Property(property="position_id", type="enum", example="4"),
     *    ),
     * ),
     *      @OA\Parameter(
     *          name="id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="correct response",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Employee data update successfully!!!"),
     *          @OA\Property(property="status", type="boolean", example="true"),
     *        ),
     *     ),
     * )
     */
    public function update(UserRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::query()->findOrFAil($id);
            $data = $request->all();
            $user->update($data);
            return response()->json(['status' => true, 'message' => 'Employee data update successfully!!!']);
        }catch (ValidationException $e) {
            return response()->json(array_values($e->errors()));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Delete existing user",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="correct response",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Employee data delete successfully!!!"),
     *          @OA\Property(property="status", type="boolean", example="true"),
     *        ),
     *     ),
     * )
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::query()->findOrFAil($id);
            $user->isDeleted = false;
            $user->save();
            return response()->json(['status' => true, 'message' => 'Employee data delete successfully!!!']);
        }catch (ValidationException $e) {
            return response()->json(array_values($e->errors()));
        }
    }
}
