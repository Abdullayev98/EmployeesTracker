<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    /**
     * @OA\Get(
     *      path="/transactions",
     *      operationId="GetTransactions",
     *      tags={"Transactions"},
     *      description="Agar search parametri bosa qidiradi, bomasa transactions larni qaytaradi ",
     *      summary="get list of transactions",
     *     @OA\Parameter(
     *          name="search",
     *          description="Userlarni qididrish first_name yoki last_name orqali",
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(
     *                  @OA\Property(property="success", type="boolean", example="true"),
     *                  @OA\Property(property="data", type="object"),
     *              ),
     *          ),
     *          @OA\Response(
     *              response=422,
     *              description="Wrong something",
     *              @OA\JsonContent(
     *                  @OA\Property(property="success", type="boolean", example="false"),
     *                  @OA\Property(property="data", type="string"),
     *              ),
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="count_og",
     *          description="Paginationlar soni",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Transactions were taken successfully"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="data", type="object"),
     *        ),
     *     ),
     *     )
     */
    public function index(): JsonResponse
    {
        $search = \request('search');
        if($search) {
            $user = User::query()->where(function ($query) use ($search) {
                $query->where('first_name', $search)
                    ->orWhere('last_name', $search);
            })->where('isDeleted', true)->get();
            if ($user) {
                return response()->json(['success' => true, 'data' => $user]);
            }
            return response()->json(['success' => false, 'data' => "There is not data"]);
        }
        $count_pagination = \request('count_pg');
        if(empty($count_pagination)){
            $count_pagination = 10;
        }
        $transactions = Paginator::advansedPaginate(DB::table('transactions as t')->join('users as u', 't.user_id', '=', 'u.id')
            ->select('t.id', 't.type', 't.where', 't.dateTime', 't.user_id',     'u.first_name', 'u.last_name')->paginate($count_pagination));
        return response()->json([
            'message' => 'Transactions were taken successfully',
            'success' => true,
            'data' => $transactions
        ]);
    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return JsonResponse
//     */
//    public function create(): JsonResponse
//    {
//        $transactionType = TransactionType::cases();
//        return response()->json([
//            'message' => 'TransactionTypes were taken successfully',
//            'success' => true,
//            'data' => $transactionType
//        ]);
//    }

    /**
     * @OA\Post(
     *      path="/transactions",
     *      operationId="StoreTransactions",
     *      tags={"Transactions"},
     *      description="create a new transaction",
     *      summary="create a new transaction",
     * @OA\RequestBody(
     *    required=true,
     *    description="create a new transaction",
     *    @OA\JsonContent(
     *       required={"type","where","dateTime"},
     *       @OA\Property(property="type", type="integer", example="4"),
     *       @OA\Property(property="where", type="integer", example="2"),
     *       @OA\Property(property="dateTime", type="dateTime", example="2022-01-27 11:29:00"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successful operation",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Transaction is saved successfully!!!"),
     *       @OA\Property(property="success", type="boolean"),
     *        ),
     *     ),
     * ),
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        try{
            $data = $request->all();
            $data['user_id'] = \auth('api')->user()->getAuthIdentifier();
            Transaction::query()->create($data);
            return response()->json(['success' => true, 'message' => 'Transaction is saved successfully!!!']);
        } catch (ValidationException $e) {
            return response()->json(array_values($e->errors()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * @OA\Get(
     *      path="/transactions/{id}/edit",
     *      operationId="EditTransactions",
     *      tags={"Transactions"},
     *      description="get a transaction data",
     *      summary="get a transaction data",
     *     @OA\Parameter(
     *          name="id",
     *          description="Kerak bo'lgan transaction id si",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Transaction was taken successfully"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="data", type="object"),
     *        ),
     *     )
     *     )
     */
    public function edit(Transaction $transaction): JsonResponse
    {
        return response()->json([
            'message' => 'Transaction was taken successfully',
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * @OA\Put(
     *      path="/transactions/{id}",
     *      operationId="updateTransaction",
     *      tags={"Transactions"},
     *      description="Returns updated transaction data",
     *      summary="Returns updated transaction data",
     *      @OA\RequestBody(
     *          required=true,
     *          description="update the transaction",
     *          @OA\JsonContent(
     *              required={"type","where","dateTime"},
     *              @OA\Property(property="type", type="integer", example="4"),
     *              @OA\Property(property="where", type="integer", example="2"),
     *              @OA\Property(property="dateTime", type="dateTime", example="2022-01-27 11:29:00"),
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="transaction id",
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
     *          @OA\Property(property="message", type="string", example="Transaction is updated successfully!!!"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *        ),
     *     ),
     * )
     */
    public function update(TransactionRequest $request, Transaction $transaction): JsonResponse
    {
        try {
            $data = $request->all();
            $data['user_id'] = \auth('api')->user()->getAuthIdentifier();
            $transaction->update($data);
            return response()->json(['success' => true, 'message' => 'Transaction is updated successfully!!!']);
        }catch (ValidationException $e) {
            return response()->json(array_values($e->errors()));
        }

    }

    /**
     * @OA\Delete(
     *      path="/transactions/{id}",
     *      operationId="deleteTransaction",
     *      tags={"Transactions"},
     *      summary="Delete existing transaction",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Transaction id",
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
     *          @OA\Property(property="message", type="string", example="Transaction is deleted successfully!!!"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *        ),
     *     ),
     * )
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        try {
            $transaction->isDeleted = false;
            $transaction->save();
            return response()->json(['success' => true, 'message' => 'Transaction is deleted successfully!!!']);
        }catch (ValidationException $e) {
            return response()->json(array_values($e->errors()));
        }
    }

    /**
     * @OA\Get(
     *      path="/transactions/user-transactions",
     *      operationId="GetUserTransactions",
     *      tags={"Transactions"},
     *      description="get list of User transactions",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="from",
     *          description="DateTime qachondan boshlashi",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="to",
     *          description="DateTime qachongacha filtrlash",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="count_og",
     *          description="Paginationlar soni",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="User transactions were taken successfully"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="data", type="object"),
     *        ),
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong credentials response",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Something wrong"),
     *          @OA\Property(property="success", type="boolean", example="false"),
     *        ),
     *     ),
     *     )
     */
    public function userTransactions()
    {
        $start_day = request('from');
        $end_day = request('to');
        $count_pagination = \request('count_pg');
        if(empty($count_pagination)){
            $count_pagination = 10;
        }
        $user_id = \auth('api')->user()->getAuthIdentifier();
        $transactions = Paginator::advansedPaginate(Transaction::query()->where('isDeleted',true)->where('user_id',$user_id)->whereBetween('dateTime', [$start_day, $end_day])->paginate($count_pagination));
        if ($transactions) {
            return response()->json([
                'message' => 'User transactions were taken successfully',
                'success' => true,
                'data' => $transactions
            ]);
        }
        return response()->json([
            'message' => 'Something wrong',
            'success' => false,
        ]);
    }


    /**
     * @OA\Get(
     *      path="/transactions/user-last-transactions",
     *      operationId="GetUserLastTransactions",
     *      tags={"Transactions"},
     *      description="get list of User Last transactions",
     *      summary="get list of User Last transactions",
     *      security={{"bearer_token":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Your last transaction is taken successfully"),
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(property="data", type="object"),
     *        ),
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong credentials response",
     *          @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="There is not this user transaction"),
     *          @OA\Property(property="success", type="boolean", example="false"),
     *        ),
     *     ),
     *     )
     */
    public function userLastTransactions(): JsonResponse
    {
        $user_id = \auth('api')->user()->getAuthIdentifier();
        $transaction = Transaction::query()->where('isDeleted',true)->where('user_id',$user_id)->latest()->first();
        if ($transaction) {
            return response()->json([
                'message' => 'Your last transaction is taken successfully',
                'success' => true,
                'data' => $transaction
            ]);
        }
        return response()->json([
            'message' => 'There is not this user transaction ',
            'success' => false,
        ]);
    }



}
