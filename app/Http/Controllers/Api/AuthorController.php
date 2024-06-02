<?php

namespace App\Http\Controllers\Api;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/authors",
     *     tags={"Authors"},
     *     summary="Get list of authors",
     *     description="Returns list of authors",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Author"))
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function index()
    {
        $author = Author::with('books')->get();

        return response()->json($author);
    }


    /**
     * @OA\Get(
     *     path="/api/authorsearch",
     *     tags={"Authors"},
     *     summary="Search authors",
     *     description="Search authors by name",
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
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Author"))
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function search(Request $request){
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'Veuillez remplir le champ'], 400);
        }

        $author = Author::where('name', 'like', '%' . $query . '%')->get();

        if ($author->isEmpty()) {
            return response()->json(['message' => 'Auteur non trouvé'], 404);
        }

        return response()->json($author, 200);
    }

    

    /**
     * @OA\Post(
     *     path="/api/author",
     *     tags={"Authors"},
     *     summary="Create a new author",
     *     description="Creates a new author with the given data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","biography","book_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="biography", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Author created successfully"
     *     ),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'biography' => 'required',
        ]);

        $author = Author::create($request->all());

        return response()->json('Auteur créé avec succès', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/author/{id}",
     *     tags={"Authors"},
     *     summary="Get an author by ID",
     *     description="Returns a single author by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Author")
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */

     public function show($id)
    {
        $author = Author::find($id);
        return response()->json($author);
    }

/**
     * @OA\Get(
     *     path="/api/authorbooks/{id}",
     *     tags={"Authors"},
     *     summary="Get the books written by each author",
     *     description="Get the books written by each author",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Author")
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function authorbooks($id){
        $author = Author::with('books')->findOrFail($id);
        $books = $author->books;
    
        return response()->json($books);
    }



    /**
     * @OA\Put(
     *     path="/api/authors/{id}",
     *     tags={"Authors"},
     *     summary="Update an author",
     *     description="Update an author by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","biography","book_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="biography", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Auteur modifié avec succès"
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'biography' => 'required',
        ]);

        $author = Author::findOrFail($id);
        $author->update($request->all());
        return response()->json('Auteur modifié avec succès', 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/authors/{id}",
     *     tags={"Authors"},
     *     summary="Delete an author",
     *     description="Deletes an author by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Auteur supprimé avec succès"
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();
        return response()->json(['message' => 'Auteur supprimé avec succès'], 200);
    }
}
