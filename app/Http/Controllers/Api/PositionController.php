<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PositionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/positions",
     *      operationId="positions",
     *      tags={"Positions"},
     *      summary="get positions list",
     *      description="get list of positions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Positions were taken successfully"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="data", type="object"),
     *        ),
     *     ),
     * )
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $positions = Position::query()->where('isDeleted',true)->get();
        return response()->json([
            'message' => 'Positions were taken successfully',
            'success' => true,
            'data' => $positions
        ]);
    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//
//    }

    /**
     * @OA\Post(
     *      path="/positions",
     *      operationId="StorePositions",
     *      tags={"Positions"},
     *      description="create a new position",
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new users",
     *    @OA\JsonContent(
     *       required={"name","description"},
     *       @OA\Property(property="name", type="string", example="junior dasturchi"),
     *       @OA\Property(property="description", type="string", example="junior dasturchi haqida ma'lumot"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successful operation",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Positions are saved successfully!!!"),
     *       @OA\Property(property="success", type="boolean"),
     *        ),
     *     ),
     * ),
     */
    public function store(PositionRequest $request): \Illuminate\Http\JsonResponse
    {
        try{
            Position::query()->create($request->all());
            return response()->json(['success' => true, 'message' => 'Positions are saved successfully!!!']);
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
//        $position = User::query()->findOrFail($id);
//        if ($position) {
//            return response()->json([
//                "message" => "Data are taken succesfully",
//                "success" => true,
//                "data" => $position
//            ]);
//        }
//
//        return response()->json([
//            'message' => 'Something wrong',
//            'success' => false,
//        ]);
//    }

    /**
     * @OA\Get(
     *      path="/positions/{id}/edit",
     *      operationId="EditPositions",
     *      tags={"Positions"},
     *      description="get a position data",
     *     @OA\Parameter(
     *          name="id",
     *          description="Kerak bo'lgan position id si",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Position was taken successfully"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="data", type="object"),
     *        ),
     *     )
     *     )
     */
    public function edit(int $id): \Illuminate\Http\JsonResponse
    {
        $position = Position::query()->findOrFail($id);
        return response()->json([
            'message' => 'Position was taken successfully',
            'success' => true,
            'data' => $position
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
     *      path="/positions/{id}",
     *      operationId="updatePosition",
     *      tags={"Positions"},
     *      description="Returns updated position data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="update the position",
     *          @OA\JsonContent(
     *              required={"name","description"},
     *              @OA\Property(property="name", type="string", example="junior dasturchi"),
     *              @OA\Property(property="description", type="string", example="junior dasturchi haqida ma'lumot"),
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="position id",
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
     *          @OA\Property(property="message", type="string", example="Position is updated successfully!!!"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *        ),
     *     ),
     * )
     */
    public function update(PositionRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $position = Position::query()->findOrFAil($id);
            $position->update($request->all());
            return response()->json(['success' => true, 'message' => 'Position is updated successfully!!!']);
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
     *      path="/positions/{id}",
     *      operationId="deletePosition",
     *      tags={"Positions"},
     *      summary="Delete existing position",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Position id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Position is deleted successfully!!!"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *        ),
     *     ),
     * )
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $position = Position::query()->findOrFAil($id);
            $position->isDeleted = false;
            $position->save();
            return response()->json(['success' => true, 'message' => 'Position is deleted successfully!!!']);
        }catch (ValidationException $e) {
            return response()->json(array_values($e->errors()));
        }
    }
}
