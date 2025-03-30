<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Services\AdService;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display a listing of the ads.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Ad::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('kpi', 'like', "%{$search}%")
                  ->orWhere('app_id', 'like', "%{$search}%")
                  ->orWhere('leadflow', 'like', "%{$search}%");
            });
        }
        
        $ads = $query->latest('created_at')->latest('id')->paginate(5)->withQueryString();
        
        return view('ads.index', compact('ads', 'search'));
    }

    /**
     * Refresh ads data from API.
     */
    public function refresh(AdService $adService)
    {
        $result = $adService->fetchAndStoreAds();
        
        if ($result['success']) {
            return redirect()->route('ads.index')->with('success', $result['message']);
        }
        
        return redirect()->route('ads.index')->with('error', $result['message']);
    }
} 