<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
   public function index()
   {
       return Project::with('client')->get();
   }

   public function store(Request $request)
   {
       $validated = $request->validate([
           'client_id' => 'required|exists:clients,id',
           'name' => 'required|string|max:255',
           'description' => 'required|string',
           'location' => 'required|string',
           'start_date' => 'required|date',
           'end_date' => 'required|date|after:start_date',
           'status' => 'required|in:planning,in_progress,completed'
       ]);

       return Project::create($validated);
   }

   public function show(Project $project)
   {
       return $project->load('client');
   }

   public function update(Request $request, Project $project)
   {
       $validated = $request->validate([
           'client_id' => 'required|exists:clients,id',
           'name' => 'required|string|max:255',
           'description' => 'required|string',
           'location' => 'required|string',
           'start_date' => 'required|date',
           'end_date' => 'required|date|after:start_date',
           'status' => 'required|in:planning,in_progress,completed'
       ]);

       $project->update($validated);
       return $project;
   }

   public function destroy(Project $project)
   {
       $project->delete();
       return response()->noContent();
   }
}