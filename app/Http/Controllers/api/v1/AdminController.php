<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Http\Requests\Admin\AdminStoreRequest;
    use App\Http\Requests\Admin\AdminUpdateRequest;
    use App\Services\Admin\AdminService;

    class AdminController extends Controller
    {
        protected AdminService $adminService;

        public function __construct(AdminService $adminService)
        {
            $this->adminService = $adminService;
        }

        /**
         * GET /admins
         */
        public function index()
        {
            return $this->adminService->index();
        }

        /**
         * POST /admins
         */
        public function store(AdminStoreRequest $request)
        {
            return $this->adminService->store($request->validated());
        }

        /**
         * PUT /admins/{id}
         */
        public function update(AdminUpdateRequest $request, int $id)
        {
            return $this->adminService->update($request->validated(), $id);
        }

        /**
         * DELETE /admins/{id}
         */
        public function destroy(int $id)
        {
            return $this->adminService->destroy($id);
        }

        /**
         * GET /admins/check-permissions
         */
        public function checkPermissions(Request $request)
        {
            return $this->adminService->checkPermissions($request->user());
        }
    }
