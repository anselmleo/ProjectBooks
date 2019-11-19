<?php


namespace App\Repositories\Contracts;


interface IBookRepository
{
    public function getBooks($perPage, $orderBy, $sort);

    public function createBook(array $params);
}
