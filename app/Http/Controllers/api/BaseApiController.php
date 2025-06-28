<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class BaseApiController extends BaseController
{
  /**
   * Lấy số lượng bản ghi mỗi trang từ request
   */
  protected function getPerPage(Request $request): int
  {
    $perPage = (int) $request->query('per_page', config('api.pagination.default_per_page'));
    $maxPerPage = config('api.pagination.max_per_page');
    $minPerPage = config('api.pagination.min_per_page');

    return max($minPerPage, min($perPage, $maxPerPage));
  }

  /**
   * Lấy cột sắp xếp từ request
   */
  protected function getSortBy(Request $request): string
  {
    return $request->query('sort_by', config('api.sorting.default_sort_by'));
  }

  /**
   * Lấy thứ tự sắp xếp từ request
   */
  protected function getSortOrder(Request $request): string
  {
    $order = strtolower($request->query('sort_order', config('api.sorting.default_sort_order')));
    $allowedOrders = config('api.sorting.allowed_sort_orders');

    return in_array($order, $allowedOrders) ? $order : config('api.sorting.default_sort_order');
  }
}