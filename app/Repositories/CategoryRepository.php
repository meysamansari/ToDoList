<?php

namespace App\Repositories;

use App\Models\category;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function index(int $user_id): Collection;
    public function show(Category $category);
    public function create(array $data);
    public function update(Category $category, array $data);
    public function delete(Category $category);
}

class CategoryRepository implements CategoryRepositoryInterface
{
    public function index(int $user_id): Collection
    {
        return Category::where('user_id', $user_id)->get();
    }


    public function show(Category $category)
    {
        return $category;
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category)
    {
        $category->delete();
    }
}
