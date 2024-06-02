<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Books Management System's API Documentation",
 *      description="API documentation for the Book Management System",
 *      @OA\Contact(
 *          email="support@example.com"
 *      )
 * )
 * @OA\Tag(
 *     name="Books",
 *     description="API Endpoints for Books"
 * )
 * @OA\Tag(
 *     name="Authors",
 *     description="API Endpoints for Authors"
 * )
 * 
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Auth",
 *     required={"name", "email", "password"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the user"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the user"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="email of the user"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="Password of the user"
 *     ),
 * )
 * 
 * @OA\Schema(
 *     schema="Author",
 *     type="object",
 *     title="Author",
 *     required={"name", "biography", "book_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the author"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the author"
 *     ),
 *     @OA\Property(
 *         property="biography",
 *         type="string",
 *         description="Biography of the author"
 *     ),
 *     @OA\Property(
 *         property="book_id",
 *         type="integer",
 *         description="ID of the book"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Book",
 *     type="object",
 *     title="Book",
 *     required={"title", "description"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the book"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the book"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the book"
 *     )
 * )
 */
class DocumentationController extends Controller{

}
