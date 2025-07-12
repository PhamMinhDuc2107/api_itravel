<?php

    namespace App\Services\Admin;

    use App\Enums\AdminStatus;
    use App\Models\Admin;
    use App\Repositories\Contracts\AdminRepositoryInterface;

    class AdminService
    {
        protected AdminRepositoryInterface $adminRepo;

        public function __construct(AdminRepositoryInterface $adminRepo)
        {
            $this->adminRepo = $adminRepo;
        }

        /**
         * Lấy danh sách admin kèm roles, permissions
         */
        public function index()
        {
            try {
                $admins = $this->adminRepo->getAll(['roles', 'permissions']);
                return response()->success($admins, 'Lấy danh sách admin thành công');
            } catch (\Exception $e) {
                return response()->error('Lỗi khi lấy danh sách admin', 500, [], $e->getMessage());
            }
        }

        /**
         * Tạo admin mới
         */
        public function store(array $data)
        {
            try {
                $data['password'] = bcrypt($data['password']);
                $data['status'] = AdminStatus::INACTIVE;

                $admin = $this->adminRepo->create($data);
                return response()->created($admin, 'Tạo admin thành công');
            } catch (\Exception $e) {
                return response()->error('Lỗi khi tạo admin', 500, [], $e->getMessage());
            }
        }

        /**
         * Cập nhật thông tin admin
         */
        public function update(array $data, int $id)
        {
            try {
                $admin = $this->adminRepo->find($id);

                if (!$admin) {
                    return response()->notFound('Không tìm thấy admin');
                }

                $admin->update($data);
                return response()->updated($admin, 'Cập nhật admin thành công');
            } catch (\Exception $e) {
                return response()->error('Lỗi khi cập nhật admin', 500, [], $e->getMessage());
            }
        }

        /**
         * Xóa admin
         */
        public function destroy(int $id)
        {
            try {
                $admin = $this->adminRepo->find($id);

                if (!$admin) {
                    return response()->notFound('Không tìm thấy admin');
                }

                $this->adminRepo->delete($id);
                return response()->deleted('Xóa admin thành công');
            } catch (\Exception $e) {
                return response()->error('Lỗi khi xóa admin', 500, [], $e->getMessage());
            }
        }

        /**
         * Kiểm tra quyền của user
         */
        public function checkPermissions($user)
        {
            try {
                return response()->success([
                    'user'              => $user,
                    'roles'             => $user->roles,
                    'permissions'       => $user->getAllPermissions(),
                    'can_view_admins'   => $user->can('view_admins'),
                    'can_create_admins' => $user->can('create_admins'),
                    'can_edit_admins'   => $user->can('edit_admins'),
                    'can_delete_admins' => $user->can('delete_admins'),
                ], 'Thông tin quyền truy cập');
            } catch (\Exception $e) {
                return response()->error('Lỗi khi kiểm tra quyền', 500, [], $e->getMessage());
            }
        }
    }
