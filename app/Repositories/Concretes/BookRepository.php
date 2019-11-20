<?php


namespace App\Repositories\Concretes;

use Exception;
use App\Models\User;
use App\Models\Book;
use App\Repositories\Contracts\IBookRepository;
use Illuminate\Support\Facades\Storage;


class BookRepository implements IBookRepository
{
    private $user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user_id
     */
    public function setUser($user_id): void
    {
        $this->user = User::find($user_id);
    }

    /**
     * @param int $user_id
     * @param array $params
     * @return void
     * @throws Exception
     */
    public function createBook(array $params)
    {
        if(file_exists($params['cover_image'])) {
        // if ($request->hasFile('cover_image')) 

        //Get full filename
        $filenameWithExt = $params['cover_image']->getClientOriginalName();

        //Extract filename only
        $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        //Extract extenstion only
        $extension = $params['cover_image']->getClientOriginalExtension();

        //Combine again with timestamp in the middle to differentiate files with same filename.
        $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;

        $path = $params['cover_image']->storeAs('public/cover_images', $filenameToStore);
        
        } else {
        $filenameToStore = 'noimage.jpg';
        }
        
        $book = Book::create([
            'title' => $params['title'],
            'description' => $params['description'],
            'cover_image' => $filenameToStore,
            'image_path' => url($path),
            'category_id' => $params['category_id'],
            'author_name' => $params['author_name']
        ]);

        return $book;
    }


    public function updateBook($book_id, array $params)
    {
        if (file_exists($params['cover_image'])) {
            // if ($request->hasFile('cover_image')) 

            //Get full filename
            $filenameWithExt = $params['cover_image']->getClientOriginalName();

            //Extract filename only
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            //Extract extenstion only
            $extension = $params['cover_image']->getClientOriginalExtension();

            //Combine again with timestamp in the middle to differentiate files with same filename.
            $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;

            $path = $params['cover_image']->storeAs('public/cover_images', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
            $path = 'public/cover_images/noimage.jpg';
        }

        if(!$book = Book::find($book_id))
            throw new Exception("Book not found!");

        $cover_image = $book->cover_image;
        if ($cover_image != 'noimage.jpg' && file_exists($params['cover_image'])) {
            Storage::delete('public/cover_images/' . $cover_image);
        }

        $book->update([
            'title' => $params['title'],
            'description' => $params['description'],
            'cover_image' => $filenameToStore,
            'image_path' => url($path),
            'category_id' => $params['category_id'],
            'author_name' => $params['author_name']
        ]);

        return $book;
    }

    public function getBooks($perPage, $orderBy, $sort)
    {
        $book_listing = Book::with(['category'])
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return $book_listing;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBook($book_id): void
    {
        //delete book
        $book = Book::find($book_id);

        if(is_null($book))
            throw new Exception("Book not found!");

        $cover_image = $book->cover_image;

        if ($cover_image != 'noimage.jpg') {
            Storage::delete('public/cover_images/' . $cover_image);
        }
        
        $book->delete();
        
    }

    /**
     * @param int $book_id
     * @param int $user_id
     * @param array $params
     * @return BookReview
     * @throws Exception
     */
    public function reviewWorker(int $job_id, int $employer_id, int $worker_id, array $params): JobReview
    {
        if (!$this->isCompleted($job_id))
            throw new Exception('This job is not completed!');

        if ($this->hasReviewed($job_id, $employer_id, $worker_id)) {
            throw new Exception('Worker has already been reviewed');
        }

        $review = JobReview::create([
            'job_id' => $job_id,
            'reviewer_id' => $employer_id,
            'reviewee_id' => $worker_id,
            'no_of_stars' => $params['no_of_stars'],
            'remark' => $params['remark']
        ]);

        return $review;
    }
    
}
