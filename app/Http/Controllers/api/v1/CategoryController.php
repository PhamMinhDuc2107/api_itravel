<?php

    namespace App\Http\Controllers\api\v1;

    use App\Http\Controllers\Api\BaseApiController;
    use App\Http\Requests\Category\CategoryRequest;
    use App\Http\Resources\CategoryResource;
    use App\Models\Category\Category;
    use Illuminate\Http\Request;

    class CategoryController extends BaseApiController
    {
        public function index(Request $request)
        {
            $perPage = $this->getPerPage($request);
            $sortBy = $this->getSortBy($request);
            $sortOrder = $this->getSortOrder($request);
            $search = htmlspecialchars(strip_tags($request->get("q"))) ?? "";

            $query = Category::with('parent');

            if ($search !== "") {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            }

            $categories = $query
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage)
                ->appends($request->query());

            return response()->paginate(
                $categories,
                "Lấy danh sách danh mục thành công"
            );
        }

        public function store(CategoryRequest $request)
        {
            try {
                $category = Category::create($request->validated());

                return response()->created(
                    new CategoryResource($category->load('parent')),
                    'Tạo danh mục thành công'
                );
            } catch (\Exception $e) {
                return response()->error(
                    'Không thể tạo danh mục',
                    500,
                    [],
                    $e->getMessage()
                );
            }
        }

        public function show(string $id)
        {
            try {
                $category = Category::with(['parent', 'children'])->findOrFail($id);

                return response()->success(
                    new CategoryResource($category),
                    'Lấy thông tin danh mục thành công'
                );
            } catch (\Exception $e) {
                return response()->notFound('Không tìm thấy danh mục');
            }
        }

        public function update(CategoryRequest $request, string $id)
        {
            try {
                $category = Category::findOrFail($id);
                $category->update($request->validated());

                return response()->updated(
                    new CategoryResource($category->load('parent')),
                    'Cập nhật danh mục thành công'
                );
            } catch (\Exception $e) {
                return response()->error(
                    'Không thể cập nhật danh mục',
                    500,
                    [],
                    $e->getMessage()
                );
            }
        }

        public function destroy(string $id)
        {
            try {
                $category = Category::findOrFail($id);

                if ($category->children()->count() > 0) {
                    return response()->error(
                        'Không thể xóa danh mục có danh mục con',
                        400
                    );
                }

                $category->delete();

                return response()->deleted('Xóa danh mục thành công');
            } catch (\Exception $e) {
                return response()->error(
                    'Không thể xóa danh mục',
                    500,
                    [],
                    $e->getMessage()
                );
            }
        }

        public function tree(Request $request)
        {
            try {
                $categories = Category::with('children')
                    ->whereNull('parent_id')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('name', 'asc')
                    ->get();

                return response()->success(
                    CategoryResource::collection($categories),
                    'Lấy cây danh mục thành công'
                );
            } catch (\Exception $e) {
                return response()->error(
                    'Không thể lấy cây danh mục',
                    500,
                    [],
                    $e->getMessage()
                );
            }
        }

        public function byStatus(Request $request, string $status)
        {
            try {
                $perPage = $this->getPerPage($request);
                $sortBy = $this->getSortBy($request);
                $sortOrder = $this->getSortOrder($request);

                $categories = Category::with('parent')
                    ->where('status', $status)
                    ->orderBy($sortBy, $sortOrder)
                    ->paginate($perPage)
                    ->appends($request->query());

                return response()->paginate(
                    $categories,
                    "Lấy danh sách danh mục {$status} thành công"
                );
            } catch (\Exception $e) {
                return response()->error(
                    'Không thể lấy danh sách danh mục',
                    500,
                    [],
                    $e->getMessage()
                );
            }
        }
    }
