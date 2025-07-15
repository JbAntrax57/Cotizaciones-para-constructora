<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponse;

   public function index()
   {
       $projects = Project::with('client')->get();
       return $this->successResponse($projects, 'Proyectos obtenidos exitosamente');
   }

   public function getByClient($clientId)
   {
       $projects = Project::where('client_id', $clientId)->get();
       return $this->successResponse($projects, 'Proyectos del cliente obtenidos exitosamente');
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

       $project = Project::create($validated);
       return $this->createdResponse($project, 'Proyecto creado exitosamente');
   }

   public function show(Project $project)
   {
       $project->load('client');
       return $this->successResponse($project, 'Proyecto obtenido exitosamente');
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
       return $this->updatedResponse($project, 'Proyecto actualizado exitosamente');
   }

   public function destroy(Project $project)
   {
       $project->delete();
       return $this->deletedResponse('Proyecto eliminado exitosamente');
   }
}