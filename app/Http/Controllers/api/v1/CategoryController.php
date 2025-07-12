<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Category\CategoryRequest;
    use App\Services\Category\CategoryService;
    use Illuminate\Http\Request;

    class CategoryController extends Controller
    {
        protected CategoryService $categoryService;

        public function __construct(CategoryService $categoryService)
        {
            $this->categoryService = $categoryService;
        }

        public function index(Request $request)
        {
            return $this->categoryService->index($request);
        }

        public function store(CategoryRequest $request)
        {
            return $this->categoryService->store($request->validated());
        }

        public function show(string $id)
        {
            return $this->categoryService->show($id);
        }

        public function update(CategoryRequest $request, string $id)
        {
            return $this->categoryService->update($request->validated(), $id);
        }

        public function destroy(string $id)
        {
            return $this->categoryService->destroy($id);
        }

        public function tree()
        {
            return $this->categoryService->tree();
        }

        public function byStatus(Request $request, string $status)
        {
            return $this->categoryService->byStatus($request, $status);
        }
    }
