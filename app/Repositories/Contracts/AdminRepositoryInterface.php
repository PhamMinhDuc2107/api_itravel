<?php

    namespace App\Repositories\Contracts;

    use App\Models\Auth\Admin;

    interface AdminRepositoryInterface
    {
        public function create(array $data): Admin;

        public function findByEmail(string $email): ?Admin;

        public function getAll(array $with = []);

        public function find(int $id): ?Admin;

        public function update(int $id, array $data): ?Admin;

        public function delete(int $id): bool;
    }
