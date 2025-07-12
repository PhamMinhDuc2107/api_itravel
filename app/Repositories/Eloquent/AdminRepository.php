<?php

    namespace App\Repositories\Eloquent;

    use App\Models\Auth\Admin;
    use App\Repositories\Contracts\AdminRepositoryInterface;
    use Illuminate\Support\Collection;

    class AdminRepository implements AdminRepositoryInterface
    {
        public function getAll(array $with = []): Collection
        {
            return Admin::with($with)->get();
        }

        public function find(int $id): ?Admin
        {
            return Admin::find($id);
        }

        public function findByEmail(string $email): ?Admin
        {
            return Admin::where('email', $email)->first();
        }

        public function create(array $data): Admin
        {
            return Admin::create($data);
        }

        public function update(int $id, array $data): ?Admin
        {
            $admin = $this->find($id);
            if ($admin) {
                $admin->update($data);
            }
            return $admin;
        }

        public function delete(int $id): bool
        {
            $admin = $this->find($id);
            if ($admin) {
                return (bool) $admin->delete();
            }
            return false;
        }
    }
