<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutPage;
use App\Models\AboutPageTeamMember;
use App\Models\AboutPageValue;
use App\Models\AboutPageRecognition;
use App\Models\AboutPageTimelineItem;
use App\Models\AboutPageStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutPageController extends BaseAdminController
{
    /**
     * Parse comma separated string or array into trimmed array
     */
    private function parseCommaSeparated($arrayValue, $stringValue)
    {
        if (is_array($arrayValue)) {
            return array_values(array_filter(array_map('trim', $arrayValue)));
        }
        
        if (is_string($stringValue) && strlen(trim($stringValue)) > 0) {
            $parts = explode(',', $stringValue);
            return array_values(array_filter(array_map('trim', $parts)));
        }
        
        return [];
    }
    
    /**
     * Display the about page management dashboard
     */
    public function index()
    {
        $sections = AboutPage::orderBy('display_order')->get();
        $teamMembers = AboutPageTeamMember::orderBy('display_order')->get();
        $values = AboutPageValue::orderBy('display_order')->get();
        $recognitions = AboutPageRecognition::orderBy('display_order')->get();
        $timelineItems = AboutPageTimelineItem::orderBy('display_order')->get();
        $statistics = AboutPageStatistic::orderBy('display_order')->get();
        
        return view('admin.about-page.index', compact(
            'sections', 
            'teamMembers', 
            'values', 
            'recognitions', 
            'timelineItems', 
            'statistics'
        ));
    }

    /**
     * Update a section
     */
    public function updateSection(Request $request, $id)
    {
        try {
            $section = AboutPage::findOrFail($id);
            
            $validated = $request->validate([
                'section_name' => 'required|string|max:255',
                'content' => 'nullable|string',
                'data_json' => 'nullable|string',
                'image_url' => 'nullable|string|max:500',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $decodedData = null;
            if ($request->filled('data_json')) {
                $decodedData = json_decode($request->input('data_json'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Invalid JSON provided for data.'], 422);
                    }
                    return back()->withErrors(['data_json' => 'Invalid JSON provided for data.'])->withInput();
                }
            }
            
            $payload = [
                'section_name' => $validated['section_name'],
                'content' => $validated['content'] ?? null,
                'image_url' => $validated['image_url'] ?? null,
                'display_order' => $validated['display_order'] ?? $section->display_order,
                'is_active' => filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN),
                'data' => $decodedData,
            ];
            
            $section->update($payload);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Section updated successfully!']);
            }
            
            return $this->successResponse('Section updated successfully!', route('admin.about-page.index'));
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Team Members Management
     */
    public function storeTeamMember(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'bio' => 'nullable|string',
                'image_url' => 'nullable|string|max:500',
                'expertise' => 'nullable|array',
                'expertise_text' => 'nullable|string',
                'social_links' => 'nullable|array',
                'social_links_text' => 'nullable|string',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            $validated['display_order'] = $validated['display_order'] ?? (AboutPageTeamMember::max('display_order') ?? 0) + 1;
            
            // Allow comma-separated text input for expertise and social links
            $validated['expertise'] = $this->parseCommaSeparated($validated['expertise'] ?? null, $validated['expertise_text'] ?? null);
            $validated['social_links'] = $this->parseCommaSeparated($validated['social_links'] ?? null, $validated['social_links_text'] ?? null);
            
            AboutPageTeamMember::create(collect($validated)->except(['expertise_text', 'social_links_text'])->toArray());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Team member created successfully!']);
            }
            
            return $this->successResponse('Team member created successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function updateTeamMember(Request $request, $id)
    {
        try {
            $member = AboutPageTeamMember::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'bio' => 'nullable|string',
                'image_url' => 'nullable|string|max:500',
                'expertise' => 'nullable|array',
                'expertise_text' => 'nullable|string',
                'social_links' => 'nullable|array',
                'social_links_text' => 'nullable|string',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            
            $validated['expertise'] = $this->parseCommaSeparated($validated['expertise'] ?? null, $validated['expertise_text'] ?? null);
            $validated['social_links'] = $this->parseCommaSeparated($validated['social_links'] ?? null, $validated['social_links_text'] ?? null);
            
            $member->update(collect($validated)->except(['expertise_text', 'social_links_text'])->toArray());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Team member updated successfully!']);
            }
            
            return $this->successResponse('Team member updated successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function deleteTeamMember($id)
    {
        $member = AboutPageTeamMember::findOrFail($id);
        $member->delete();
        
        return $this->successResponse('Team member deleted successfully!', route('admin.about-page.index'));
    }

    /**
     * Values Management
     */
    public function storeValue(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'icon' => 'nullable|string|max:100',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            $validated['display_order'] = $validated['display_order'] ?? (AboutPageValue::max('display_order') ?? 0) + 1;
            
            AboutPageValue::create($validated);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Value created successfully!']);
            }
            
            return $this->successResponse('Value created successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function updateValue(Request $request, $id)
    {
        try {
            $value = AboutPageValue::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'icon' => 'nullable|string|max:100',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            
            $value->update($validated);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Value updated successfully!']);
            }
            
            return $this->successResponse('Value updated successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function deleteValue($id)
    {
        $value = AboutPageValue::findOrFail($id);
        $value->delete();
        
        return $this->successResponse('Value deleted successfully!', route('admin.about-page.index'));
    }

    /**
     * Recognitions Management
     */
    public function storeRecognition(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:100',
                'year' => 'nullable|string|max:50',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            $validated['display_order'] = $validated['display_order'] ?? (AboutPageRecognition::max('display_order') ?? 0) + 1;
            
            AboutPageRecognition::create($validated);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Recognition created successfully!']);
            }
            
            return $this->successResponse('Recognition created successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function updateRecognition(Request $request, $id)
    {
        try {
            $recognition = AboutPageRecognition::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:100',
                'year' => 'nullable|string|max:50',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            
            $recognition->update($validated);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Recognition updated successfully!']);
            }
            
            return $this->successResponse('Recognition updated successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function deleteRecognition($id)
    {
        $recognition = AboutPageRecognition::findOrFail($id);
        $recognition->delete();
        
        return $this->successResponse('Recognition deleted successfully!', route('admin.about-page.index'));
    }

    /**
     * Timeline Items Management
     */
    public function storeTimelineItem(Request $request)
    {
        try {
            $validated = $request->validate([
                'year' => 'required|string|max:50',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            $validated['display_order'] = $validated['display_order'] ?? (AboutPageTimelineItem::max('display_order') ?? 0) + 1;
            
            AboutPageTimelineItem::create($validated);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Timeline item created successfully!']);
            }
            
            return $this->successResponse('Timeline item created successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function updateTimelineItem(Request $request, $id)
    {
        try {
            $item = AboutPageTimelineItem::findOrFail($id);
            
            $validated = $request->validate([
                'year' => 'required|string|max:50',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            
            $item->update($validated);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Timeline item updated successfully!']);
            }
            
            return $this->successResponse('Timeline item updated successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function deleteTimelineItem($id)
    {
        $item = AboutPageTimelineItem::findOrFail($id);
        $item->delete();
        
        return $this->successResponse('Timeline item deleted successfully!', route('admin.about-page.index'));
    }

    /**
     * Statistics Management
     */
    public function storeStatistic(Request $request)
    {
        try {
            $validated = $request->validate([
                'label' => 'required|string|max:255',
                'value' => 'required|string|max:255',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:100',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            $validated['display_order'] = $validated['display_order'] ?? (AboutPageStatistic::max('display_order') ?? 0) + 1;
            
            AboutPageStatistic::create($validated);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Statistic created successfully!']);
            }
            
            return $this->successResponse('Statistic created successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function updateStatistic(Request $request, $id)
    {
        try {
            $statistic = AboutPageStatistic::findOrFail($id);
            
            $validated = $request->validate([
                'label' => 'required|string|max:255',
                'value' => 'required|string|max:255',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:100',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $validated['is_active'] = filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN);
            
            $statistic->update($validated);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Statistic updated successfully!']);
            }
            
            return $this->successResponse('Statistic updated successfully!', route('admin.about-page.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function deleteStatistic($id)
    {
        $statistic = AboutPageStatistic::findOrFail($id);
        $statistic->delete();
        
        return $this->successResponse('Statistic deleted successfully!', route('admin.about-page.index'));
    }
}
