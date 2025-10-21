<?php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use Illuminate\Http\Request;

class LandingContentController extends Controller
{
    /**
     * Get all landing page content grouped by section
     */
    public function index()
    {
        $contents = LandingContent::all();
        
        $grouped = $contents->groupBy('section')->map(function ($items) {
            return $items->mapWithKeys(function ($item) {
                return [$item->key => [
                    'value' => $item->value,
                    'metadata' => $item->metadata
                ]];
            });
        });
        
        return response()->json([
            'success' => true,
            'data' => $grouped
        ]);
    }

    /**
     * Get content by section
     */
    public function getBySection($section)
    {
        $contents = LandingContent::where('section', $section)->get();
        
        $data = $contents->mapWithKeys(function ($item) {
            return [$item->key => [
                'value' => $item->value,
                'metadata' => $item->metadata
            ]];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update or create content
     */
    public function upsert(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|string',
            'metadata' => 'nullable|array'
        ]);

        $content = LandingContent::updateOrCreate(
            [
                'section' => $validated['section'],
                'key' => $validated['key']
            ],
            [
                'value' => $validated['value'],
                'metadata' => $validated['metadata'] ?? null
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Content updated successfully',
            'data' => $content
        ]);
    }

    /**
     * Bulk update multiple contents
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'contents' => 'required|array',
            'contents.*.section' => 'required|string',
            'contents.*.key' => 'required|string',
            'contents.*.value' => 'required|string',
            'contents.*.metadata' => 'nullable|array'
        ]);

        foreach ($validated['contents'] as $contentData) {
            LandingContent::updateOrCreate(
                [
                    'section' => $contentData['section'],
                    'key' => $contentData['key']
                ],
                [
                    'value' => $contentData['value'],
                    'metadata' => $contentData['metadata'] ?? null
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Contents updated successfully'
        ]);
    }

    /**
     * Delete content
     */
    public function destroy($section, $key)
    {
        $content = LandingContent::where('section', $section)
            ->where('key', $key)
            ->first();

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found'
            ], 404);
        }

        $content->delete();

        return response()->json([
            'success' => true,
            'message' => 'Content deleted successfully'
        ]);
    }
}
