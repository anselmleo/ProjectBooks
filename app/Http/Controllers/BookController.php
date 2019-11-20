<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\IBookRepository;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Models\User;
use App\Utils\Rules;
use Exception;

class BookController extends Controller
{
  /**
   * @var IBookRepository
   */
  private $bookRepository;
  private $user;
  private $book;

  
  public function __construct(IBookRepository $bookRepository)
  {
    $this->middleware('role:super_admin|admin', ['except' => ['getBooks', 'review']]);
    $this->middleware('auth:api', ['only' => ['review']]);
    $this->bookRepository = $bookRepository;
  }
  
  /**
     * @OA\Post(
     *     path="/books",
     *     operationId="createBook",
     *     tags={"Book Operations"},
     *     security={{"authorization_token": {}}},
     *     summary="Create a new book",
     *     description="Can only be performed by an admin",
     *     @OA\RequestBody(
     *       required=true,
     *       description="Request object",
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="title",
     *                  description="Title",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  description="Description",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="cover_image",
     *                  description="Cover image",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="category_id",
     *                  description="Category ID",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="author_id",
     *                  description="Author ID",
     *                  type="string",
     *              )
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns response object",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Error: Unproccessble Entity. When required parameters were not supplied correctly.",
     *          @OA\JsonContent()
     *     )
     * )
     */
    public function createBook()
    {
        $validator = Validator::make(request()->all(), Rules::get('CREATE_BOOK'));
        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }

        try {
            $book = $this->bookRepository->createBook(request()->all());
            return $this->withSuccessAndData("Book created successfully", $book);
        } catch(Exception $e) {
            return $this->error($e->getMessage());
        }
    }

  /**
   * @OA\Patch(
   *     path="/book/{book_id}/update",
   *     operationId="updateBook",
   *     tags={"Book Operations"},
   *     security={{"authorization_token": {}}},
   *     summary="Update book",
   *     description="Can only be performed by an authenticated user",
   *     @OA\Parameter(
   *         name="book_id",
   *         in="path",
   *         description="ID of book to update",
   *         required=true,
   *         @OA\Schema(
   *             type="integer",
   *             format="int64"
   *         )
   *     ),
   *     @OA\RequestBody(
   *       required=true,
   *       description="Request object",
   *       @OA\MediaType(
   *           mediaType="application/json",
   *           @OA\Schema(
   *              type="object",
   *              @OA\Property(
   *                  property="title",
   *                  description="Title",
   *                  type="string",
   *              ),
   *              @OA\Property(
   *                  property="description",
   *                  description="Description",
   *                  type="string",
   *              ),
   *              @OA\Property(
   *                  property="cover_image",
   *                  description="Cover image",
   *                  type="string",
   *              ),
   *              @OA\Property(
   *                  property="category_id",
   *                  description="Category ID",
   *                  type="string",
   *              ),
   *              @OA\Property(
   *                  property="author_id",
   *                  description="Author ID",
   *                  type="string",
   *              )
   *           )
   *       )
   *     ),
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     ),
   *     @OA\Response(
   *          response="422",
   *          description="Error: Unproccessble Entity. When required parameters were not supplied correctly.",
   *          @OA\JsonContent()
   *     )
   * )
   * @param $book_id
   * @return JsonResponse
   */

  public function updateBook($book_id)
  {

    $validator = Validator::make(request()->all(), Rules::get('CREATE_BOOK'));
    if ($validator->fails()) {
      return $this->validationErrors($validator->getMessageBag()->all());
    }

    try {

      $book = $this->bookRepository->updateBook($book_id, request()->all());
      return $this->withSuccessAndData("Book updated successfully", $book);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }


/**
     * @OA\Get(
     *     path="book",
     *     operationId="getBooks",
     *     tags={"Book Operations"},
     *     summary="Get all book listings",
     *     description="Can be performed by anyone",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number per page",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         description="Order by a column",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="desc or asc",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns response object",
     *         @OA\JsonContent()
     *     ),
     * )
     * @return JsonResponse
     */
    public function getBooks()
    {
        try {
            $payload = request()->all();
            $perPage = request()->has('per_page') ? $payload['per_page'] : 15;
            $orderBy = request()->has('order_by') ? $payload['order_by'] : 'created_at';
            $sort = request()->has('sort') ? $payload['sort'] : 'desc';

            $books = $this->bookRepository->getBooks($perPage, $orderBy, $sort);

            return $this->withData($books);

        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }


  /**
   * @OA\Delete(
   *     path="/book/{book_id}/delete",
   *     operationId="deleteBook",
   *     tags={"Book Operations"},
   *     security={{"authorization_token": {}}},
   *     summary="Delete a book",
   *     description="Can only be performed by admin",
   *     @OA\Parameter(
   *         name="book_id",
   *         in="path",
   *         description="ID of book to delete",
   *         required=true,
   *         @OA\Schema(
   *             type="integer",
   *             format="int64"
   *         )
   *     ),
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     ),
   * )
   * @param $job_id
   * @return JsonResponse
   */



  
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function deleteBook($book_id)
  {
    
    try {
      $this->bookRepository->deleteBook($book_id);
      return $this->success("Book deleted successfully");
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * @OA\Patch(
   *     path="/book/{book_id}/review",
   *     operationId="review",
   *     tags={"Book Operations"},
   *     security={{"authorization_token": {}}},
   *     summary="Review a book",
   *     description="Can only be performed by an authenticated user",
   *     @OA\Parameter(
   *         name="book_id",
   *         in="path",
   *         description="ID of book to review",
   *         required=true,
   *         @OA\Schema(
   *             type="integer",
   *             format="int64"
   *         )
   *     ),
   *     @OA\RequestBody(
   *       required=true,
   *       description="Request object",
   *       @OA\MediaType(
   *           mediaType="application/json",
   *           @OA\Schema(
   *              type="object",
   *              @OA\Property(
   *                  property="no_of_stars",
   *                  description="No of stars",
   *                  type="string",
   *              ),
   *              @OA\Property(
   *                  property="remark",
   *                  description="Remarks",
   *                  type="string",
   *              ),
   *           )
   *       )
   *     ),
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     )
   * )
   * @param $worker_id
   * @param $job_id
   * @return JsonResponse
   */
  public function review($book_id)
  {
    $validator = Validator::make(request()->all(), Rules::get('BOOK_REVIEW'));
    if ($validator->fails()) {
      return $this->validationErrors($validator->getMessageBag()->all());
    }

    try {
      
      $bookReview = $this->bookRepository->review($book_id, auth()->id(), request()->all());
      return $this->withSuccessAndData("Book reviewed successfully", $bookReview);

    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

}
