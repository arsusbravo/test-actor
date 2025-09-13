<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Inertia\Inertia;

class ActorController extends Controller
{
    public function index()
    {
        $actors = Actor::select('first_name', 'address', 'gender', 'height')
            ->whereNotNull('first_name')
            ->get();

        return Inertia::render('Actors/Index', [
            'actors' => $actors
        ]);
    }

    public function create()
    {
        return Inertia::render('Actors/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:actors,email',
            'description' => 'required|min:10',
        ]);

        try {
            // Check if OpenAI API key is configured
            $apiKey = config('openai.api_key');
            if (empty($apiKey) || $apiKey === 'your-openai-api-key-here') {
                \Log::error('OpenAI API key not configured');
                return back()->withErrors(['description' => 'OpenAI API key not configured. Please add your API key to .env file.']);
            }

            // For testing - use mock data to avoid rate limits
            $useMockData = true; // Set to false when you want to use real OpenAI API
            
            if ($useMockData) {
                \Log::info('Using mock data to avoid rate limits');
                $data = [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'address' => '123 Main Street, New York, NY 10001',
                    'height' => '6 feet 2 inches',
                    'weight' => '180 lbs',
                    'gender' => 'Male',
                    'age' => 30
                ];
            } else {
                // Call OpenAI API
                $response = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Extract actor info. Return JSON: first_name, last_name, address, height, weight, gender, age.'],
                        ['role' => 'user', 'content' => $request->description]
                    ]
                ]);

                \Log::info('OpenAI Response:', ['response' => $response->toArray()]);

                $data = json_decode($response->choices[0]->message->content, true);
            }
            
            \Log::info('Parsed Data:', ['data' => $data]);

            // Check required fields
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['address'])) {
                \Log::warning('Missing required fields:', ['data' => $data]);
                return back()->withErrors(['description' => 'Please add first name, last name, and address']);
            }

            // Save actor to database
            Actor::create([
                'email' => $request->email,
                'description' => $request->description,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'address' => $data['address'],
                'height' => $data['height'] ?? null,
                'weight' => $data['weight'] ?? null,
                'gender' => $data['gender'] ?? null,
                'age' => $data['age'] ?? null,
            ]);

            return redirect('/actors')->with('success', 'Success!');

        } catch (\Exception $e) {
            \Log::error('OpenAI Processing Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'description' => $request->description
            ]);
            return back()->withErrors(['description' => 'Processing failed: ' . $e->getMessage()]);
        }
    }

    public function promptValidation()
    {
        return response()->json(['message' => 'text_prompt']);
    }
}
