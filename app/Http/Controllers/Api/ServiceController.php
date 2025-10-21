<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Service::active();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $services = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }

    /**
     * Get MC services only.
     */
    public function mcServices(): JsonResponse
    {
        $services = Service::mcServices()->active()->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Get Wedding Organizer services only.
     */
    public function weddingOrganizerServices(): JsonResponse
    {
        $services = Service::weddingOrganizerServices()->active()->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }
}
