<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\IBookRepository;
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

  //////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////
  // NEW CODES TO BE REFACTORED //
  
  public function __construct(IBookRepository $bookRepository)
  {
    $this->middleware('auth:api', ['except' => ['index', 'show']]);

    $this->bookRepository = $bookRepository;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    
    try {
      // get request to /books is handled here
      // $books = Book::all();
      // $books = Book::orderBy('id', 'desc')->get();
      $books = Book::orderBy('id', 'desc')->paginate(15);
      return $this->withData($books);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }
  

  



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required',
      'category' => 'required',
      // 'cover_image' => 'image|nullable|max:1999'
      'author' => 'required'
    ]);

    // if ($request->hasFile('cover_image')) {
    //   //Get full filename
    //   $filenameWithExt = $request->file('cover_image')->getClientOriginalName();

    //   //Extract filename only
    //   $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

    //   //Extract extenstion only
    //   $extension = $request->file('cover_image')->getClientOriginalExtension();

    //   //Combine again with timestamp in the middle to differentiate files with same filename.
    //   $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;
    //   $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);
    // } else {
    //   $filenameToStore = 'noimage.jpg';
    // }


    $book = new Book;
    $book->title = $request->get('title');
    $book->body = $request->get('body');
    $book->user_id = auth()->user()->id;
    $book->cover_image = $filenameToStore;
    $book->save();

    return redirect('/dashboard')->with('success', 'Book created!');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //show individual books
    $book = Book::find($id);
    return view('books.show')->with('book', $book);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //load the book to edit
    $book = Book::find($id);
    if (auth()->user()->id !== $book->user_id) {
      return redirect()->back()->with('error', 'You can\'t access this page!');
    } else {
      return view('books.edit')->with('book', $book);
    }
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
    //
    $this->validate($request, [
      'title' => 'required|min:4',
      'body' => 'required|min:5',
      'cover_image' => 'image|nullable|max:1999'
    ]);

    if ($request->hasFile('cover_image')) {
      //Get full filename
      $filenameWithExt = $request->file('cover_image')->getClientOriginalName();

      //Extract filename only
      $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

      //Extract extenstion only
      $extension = $request->file('cover_image')->getClientOriginalExtension();

      //Combine again with timestamp in the middle to differentiate files with same filename.
      $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;
      $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);
    }

    $book = Book::find($id);
    $book->title = $request->get('title');
    $book->body = $request->get('body');
    if ($request->hasFile('cover_image')) {
      $book->cover_image = $filenameToStore;
    }
    $book->save();

    return redirect('/dashboard')->with('success', 'Book updated!');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //delete book
    $book = Book::find($id);
    if (auth()->user()->id !== $book->user_id) {
      return redirect('/books')->with('error', 'You can\'t access this page!');
    } else {
      $cover_image = $book->cover_image;
      if ($cover_image != 'noimage.jpg') {
        Storage::delete('public/cover_images/' . $cover_image);
      }
      $book->delete();
      return redirect('/dashboard')->with('success', 'Book removed!');
    }
  }
}
