<?php

    namespace App\Repositories\Contracts;

    use App\Models\Category\Category;
    use Illuminate\Http\Request;
    use Illuminate\Contracts\Pagination\LengthAwarePaginator;

    interface CategoryRepositoryInterface
    {
        public function paginate(Request $request): LengthAwarePaginator;
        public function create(array $data): Category;
        public function find(int|string $id): ?Category;
        public function update(int|string $id, array $data): ?Category;
        public function delete(int|string $id): bool;
        public function getTree(): \Illuminate\Support\Collection;
    }
