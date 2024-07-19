<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:To Do,In Progress,Done',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'user_id' => Auth::id(),
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, string $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:To Do,In Progress,Done',
            'priority' => 'sometimes|required|in:Low,Medium,High',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $task->update($request->all());

        return response()->json($task);
    }

    public function changeStatus(Request $request, string $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:To Do,In Progress,Done',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $task->update(['status' => $request->input('status')]);

        return response()->json($task);
    }

    public function destroy(string $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}
