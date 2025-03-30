<?php

namespace App\Services;

use App\Models\Ad;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdService
{
    /**
     * Fetch ads from the API and store them in the database
     *
     * @return array
     */
    public function fetchAndStoreAds(): array
    {
        $apiKey = env('API_KEY');
        $apiUrl = "https://api.kypi.io/feed/v1.2/ads?api_key={$apiKey}";
        
        try {
            $response = Http::get($apiUrl);
            
            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch data from API. Status: ' . $response->status(),
                ];
            }
            
            $data = $response->json();
            
            if (!isset($data['ads']) || !is_array($data['ads'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid data structure received from API',
                ];
            }
            
            $count = 0;
            
            foreach ($data['ads'] as $adData) {
                // Skip if we don't have a name
                if (empty($adData['name'])) {
                    continue;
                }
                
                // Check if the ad already exists by external_id
                $externalId = $adData['id'] ?? null;
                
                try {
                    $ad = null;
                    if ($externalId) {
                        $ad = Ad::where('external_id', $externalId)->first();
                    }
                    
                    // Prepare ad data
                    $adAttributes = [
                        'external_id' => $externalId,
                        'name' => $adData['name'],
                        'description' => $adData['description'] ?? null,
                        'additional_information' => $adData['additional_information'] ?? null,
                        'app_id' => $adData['app_id'] ?? null,
                        'click_url' => $adData['click_url'] ?? null,
                        'creatives_url' => $adData['creatives_url'] ?? null,
                        'kpi' => $adData['kpi'] ?? null,
                        'leadflow' => $adData['leadflow'] ?? null,
                        'payout' => $adData['payout'] ?? null,
                        'payout_currency' => $adData['payout_currency'] ?? null,
                        'preview_url' => $adData['preview_url'] ?? null,
                        'restrictions' => $adData['restrictions'] ?? null,
                        'targeting' => $adData['targeting'] ?? null,
                        'app_categories' => $adData['app_categories'] ?? [],
                        'icons' => $adData['icons'] ?? [],
                        'thumbs' => $adData['thumbs'] ?? [],
                        'event_types' => $adData['eventTypes'] ?? [],
                        'landing_page_html_templates' => $adData['landing_page_html_templates'] ?? [],
                        'sub_sources' => $adData['subSources'] ?? [],
                        'raw_data' => $adData,
                    ];
                    
                    if ($ad) {
                        // Update existing ad
                        $ad->update($adAttributes);
                    } else {
                        // Check again by external_id to avoid race conditions
                        if ($externalId) {
                            Ad::updateOrCreate(['external_id' => $externalId], $adAttributes);
                        } else {
                            // Create new ad without external_id
                            Ad::create($adAttributes);
                        }
                        $count++;
                    }
                } catch (\Exception $e) {
                    Log::warning('Error processing ad: ' . $e->getMessage(), [
                        'external_id' => $externalId,
                        'name' => $adData['name'] ?? 'Unknown'
                    ]);
                    continue;
                }
            }
            
            return [
                'success' => true,
                'message' => "Successfully processed data. Added {$count} new ads.",
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching ads: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error processing data: ' . $e->getMessage(),
            ];
        }
    }
} 