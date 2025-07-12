<?php

    namespace App\Services\Category;

    use App\Repositories\Contracts\CategoryRepositoryInterface;
    use App\Http\Resources\CategoryResource;
    use Illuminate\Http\Request;

    class CategoryService
    {
        protected CategoryRepositoryInterface $categoryRepo;

        public function __construct(CategoryRepositoryInterface $categoryRepo)
        {
            $this->categoryRepo = $categoryRepo;
        }

        public function index(Request $request)
        {
            try {
                $categories = $this->categoryRepo->paginate($request);
                return response()->paginate($categories, 'Lấy danh sách danh mục thành công');
            } catch (\Exception $e) {
                return response()->error('Không thể lấy danh sách danh mục', 500, [], $e->getMessage());
            }
        }

        public function store(array $data)
        {
            try {
                $category = $this->categoryRepo->create($data);
                return response()->created(new CategoryResource($category->load('parent')), 'Tạo danh mục thành công');
            } catch (\Exception $e) {
                return response()->error('Không thể tạo danh mục', 500, [], $e->getMessage());
            }
        }

        public function show(string|int $id)
        {
            $category = $this->categoryRepo->find($id);
            if (!$category) {
                return response()->notFound('Không tìm thấy danh mục');
            }
            return response()->success(new CategoryResource($category), 'Lấy thông tin danh mục thành công');
        }

        public function update(array $data, string|int $id)
        {
            $category = $this->categoryRepo->update($id, $data);
            if (!$category) {
                return response()->notFound('Không tìm thấy danh mục');
            }
            return response()->updated(new CategoryResource($category->load('parent')), 'Cập nhật danh mục thành công');
        }

        public function destroy(string|int $id)
        {
            $deleted = $this->categoryRepo->delete($id);
            if (!$deleted) {
                return response()->error('Không thể xóa danh mục (có thể do có danh mục con)', 400);
            }
            return response()->deleted('Xóa danh mục thành công');
        }

        public function tree()
        {
            try {
                $categories = $this->categoryRepo->getTree();
                return response()->success(CategoryResource::collection($categories), 'Lấy cây danh mục thành công');
            } catch (\Exception $e) {
                return response()->error('Không thể lấy cây danh mục', 500, [], $e->getMessage());
            }
        }
    }
