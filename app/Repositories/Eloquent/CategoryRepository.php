<?php

    namespace App\Repositories\Eloquent;

    use App\Models\Category\Category;
    use App\Repositories\Contracts\CategoryRepositoryInterface;
    use Illuminate\Http\Request;
    use Illuminate\Contracts\Pagination\LengthAwarePaginator;

    class CategoryRepository implements CategoryRepositoryInterface
    {
        public function paginate(Request $request): LengthAwarePaginator
        {
            $query = Category::with('parent');

            if (!empty($request['search'])) {
                $search = $request['search'];
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            }

            return $query
                ->orderBy($request['sort_by'] ?? 'id', $request['sort_order'] ?? 'desc')
                ->paginate($request['per_page'] ?? 15)
                ->appends($request['query'] ?? []);
        }

        public function create(array $data): Category
        {
            return Category::create($data);
        }

        public function find(int|string $id): ?Category
        {
            return Category::with(['parent', 'children'])->find($id);
        }

        public function update(int|string $id, array $data): ?Category
        {
            $category = $this->find($id);
            if ($category) {
                $category->update($data);
            }
            return $category;
        }

        public function delete(int|string $id): bool
        {
            $category = $this->find($id);
            if ($category && $category->children()->count() === 0) {
                return (bool) $category->delete();
            }
            return false;
        }

        public function getTree(): \Illuminate\Support\Collection
        {
            return Category::with('children')
                ->whereNull('parent_id')
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        }
    }
