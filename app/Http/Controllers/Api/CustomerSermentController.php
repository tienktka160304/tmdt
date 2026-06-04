<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\CustomerSerment;

class CustomerSermentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function getAllcustomer()
  {
    $doituong = CustomerSerment::all();
    return response()->json([
      'status' => 'success',
      'data' => $doituong
    ]);
  }
}
