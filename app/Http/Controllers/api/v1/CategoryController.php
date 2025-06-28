<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $sortBy = $this->getSortBy($request);
        $sortOrder = $this->getSortOrder($request);
        $search = htmlspecialchars(strip_tags($request->get("q"))) ?? "";

        $query = Category::with('parent');

        if ($search !== "") {
            $query->where('name', 'like', '%' . $search . '%')->orWhere("slug", "like", "%" . $search . '%');
        }

        $categories = $query
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage)
            ->appends($request->query());

        return response()->paginate($categories, "Lấy danh sách danh mục thành công", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tạo danh mục thành công',
                'data' => new CategoryResource($category->load('parent')),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo danh mục',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::with(['parent'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin danh mục thành công',
                'data' => new CategoryResource($category),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật danh mục thành công',
                'data' => new CategoryResource($category->load('parent')),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật danh mục',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);

            // Kiểm tra xem có danh mục con không
            if ($category->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa danh mục có danh mục con',
                ], 400);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa danh mục thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa danh mục',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get category tree structure
     */
    public function tree(Request $request)
    {
        try {
            $categories = Category::with('children')
                ->whereNull('parent_id')
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Lấy cây danh mục thành công',
                'data' => CategoryResource::collection($categories),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy cây danh mục',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get categories by status
     */
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

            return response()->json([
                'success' => true,
                'message' => "Lấy danh sách danh mục {$status} thành công",
                'data' => CategoryResource::collection($categories),
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                    'last_page' => $categories->lastPage(),
                    'from' => $categories->firstItem(),
                    'to' => $categories->lastItem(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách danh mục',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}