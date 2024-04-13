<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $tasks = $this->categoryRepository->index(auth()->id());
        return CategoryResource::collection($tasks);
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        $category = $this->categoryRepository->show($category);
        return new CategoryResource($category);
    }

    public function store(CategoryStoreRequest $request)
    {
        $this->authorize('create', Category::class);
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $category = $this->categoryRepository->create($data);
        return new CategoryResource($category);
    }

    public function update(CategoryUpdateRequest $request,Category $category)
    {
        $this->authorize('update', $category);
        $validatedData = $request->validated();
        $updatedCategory = $this->categoryRepository->update($category, $validatedData);
        return new categoryResource($updatedCategory);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category = $this->categoryRepository->show($category);
        $this->categoryRepository->delete($category);
        return response()->json(['message' => 'category deleted successfully']);
    }
}
