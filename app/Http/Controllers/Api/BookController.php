<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/book",
     *     tags={"Books"},
     *     summary="Get list of books",
     *     description="Returns list of books",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    

    public function index()
    {
        $book = Book::with('authors')->get();
        return response()->json($book);
    }



    /**
     * @OA\Get(
     *     path="/api/booksearch",
     *     tags={"Books"},
     *     summary="Search books",
     *     description="Search books by title",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Search query string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */


    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'Veuillez remplir le champ'], 400);
        }

        $books = Book::where('title', 'like', '%' . $query . '%')->get();

        if ($books->isEmpty()) {
            return response()->json(['message' => 'Pas de livre trouvé'], 404);
        }

        return response()->json($books, 200);
    }

    

    /**
     * @OA\Post(
     *     path="/api/book",
     *     tags={"Books"},
     *     summary="Create a new book",
     *     description="Create a new book with the given data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book created successfully"
     *     ),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $book = Book::create($request->all());

        return response()->json('Livre créé avec succès', 201);
    }


    /**
     * @OA\Post(
     *     path="/api/affect/{id}",
     *     tags={"Books"},
     *     summary="Assign an author",
     *     description="Assign author names to each book in the library",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"authorIds"},
     *             @OA\Property(property="authorIds", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Auteur affecté avec succès"
     *     ),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function affectAuthors(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $authorIds = $request->input('author_ids');

        if (!$authorIds || !is_array($authorIds)) {
            return response()->json(['error' => 'Error'], 400);
        }

        $book->authors()->sync($authorIds);

        return response()->json('Auteur affecté avec succès', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/book/{id}",
     *     tags={"Books"},
     *     summary="Get a book by ID",
     *     description="Returns a single book by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Book")
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function show($id){
        $book = Book::with('authors')->findOrFail($id);
        $book->increment('views');

        return response()->json($book);
    }


    /**
     * @OA\Get(
     *     path="/api/leaderboard",
     *     tags={"Books"},
     *     summary="Get a list of books ordered by views",
     *     description="Returns a list of books along with their authors, ordered by the number of views in descending order.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
     *     ),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */

    public function leaderbord(){
        $books = Book::with('authors')->orderBy('views', 'desc')->get();
        return response()->json($books);
    }

    /**
     * @OA\Put(
     *     path="/api/book/{id}",
     *     tags={"Books"},
     *     summary="Update a book",
     *     description="Updates a book by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book updated successfully"
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());
        return response()->json('Livre modifié avec succès', 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/book/{id}",
     *     tags={"Books"},
     *     summary="Delete a book",
     *     description="Deletes a book by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book deleted successfully"
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json(['message' => 'Livre supprimé avec succès'], 200);
    }
}
