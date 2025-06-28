<?php

namespace App\Macros;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponseMacro
{
  public static function register(): void
  {
    Response::macro('success', function (
      mixed $data = [],
      string $message = 'Thành công',
      int $status = 200
    ): JsonResponse {
      return response()->json([
        'success' => true,
        'message' => $message,
        'data' => $data,
        'timestamp' => now()->toISOString()
      ], $status);
    });

    Response::macro('error', function (
      string $message = 'Đã có lỗi xảy ra',
      int $status = 400,
      array $errors = [],
      mixed $data = null
    ): JsonResponse {
      $response = [
        'success' => false,
        'message' => $message,
        'timestamp' => now()->toISOString()
      ];

      if (!empty($errors)) {
        $response['errors'] = $errors;
      }

      if ($data !== null) {
        $response['data'] = $data;
      }

      return response()->json($response, $status);
    });

    Response::macro('paginate', function (
      LengthAwarePaginator $paginator,
      string $message = 'Thành công',
      int $status = 200
    ): JsonResponse {
      return response()->json([
        'success' => true,
        'message' => $message,
        'data' => $paginator->items(),
        'pagination' => [
          'total' => $paginator->total(),
          'per_page' => $paginator->perPage(),
          'current_page' => $paginator->currentPage(),
          'last_page' => $paginator->lastPage(),
          'from' => $paginator->firstItem(),
          'to' => $paginator->lastItem(),
          'has_more_pages' => $paginator->hasMorePages()
        ],
        'timestamp' => now()->toISOString()
      ], $status);
    });

    Response::macro('created', function (
      mixed $data = [],
      string $message = 'Tạo thành công'
    ): JsonResponse {
      return Response::success($data, $message, 201);
    });

    Response::macro('updated', function (
      mixed $data = [],
      string $message = 'Cập nhật thành công'
    ): JsonResponse {
      return Response::success($data, $message, 200);
    });

    Response::macro('deleted', function (
      string $message = 'Xóa thành công'
    ): JsonResponse {
      return Response::success([], $message, 200);
    });

    Response::macro('notFound', function (
      string $message = 'Không tìm thấy dữ liệu'
    ): JsonResponse {
      return Response::error($message, 404);
    });

    Response::macro('unauthorized', function (
      string $message = 'Không có quyền truy cập'
    ): JsonResponse {
      return Response::error($message, 401);
    });

    Response::macro('forbidden', function (
      string $message = 'Truy cập bị từ chối'
    ): JsonResponse {
      return Response::error($message, 403);
    });

    Response::macro('validationError', function (
      array $errors,
      string $message = 'Dữ liệu không hợp lệ'
    ): JsonResponse {
      return Response::error($message, 422, $errors);
    });
  }
}