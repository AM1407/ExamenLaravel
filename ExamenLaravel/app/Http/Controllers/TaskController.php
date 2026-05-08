<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user', 'comments')->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        Task::create($request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]));

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $users = User::all();
        $task->load('comments.user');
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($request->status === 'in_progress' && $task->status !== 'in_progress') {
            $validated['started_at'] = Carbon::now();
        }

        if ($request->status === 'completed' && $task->status !== 'completed') {
            $this->notifySlack($task);
        }

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    private function notifySlack(Task $task): void
    {
        $webhookUrl = config('services.slack.webhook_url');
        if (!$webhookUrl) return;

        $payload = [
            'text' => "✅ Task Completed",
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "*{$task->title}*\nCompleted by {$task->user->name}."
                    ]
                ]
            ]
        ];
        \Illuminate\Support\Facades\Http::post($webhookUrl, $payload);
    }
}
