@extends('layouts.app')

@section('title', 'All Tasks')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="margin: 0;">Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Create New Task</a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: #333;">Task</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: #333;">Assigned To</th>
                    <th style="text-align: center; padding: 1rem; font-weight: 600; color: #333;">Status</th>
                    <th style="text-align: center; padding: 1rem; font-weight: 600; color: #333;">Comments</th>
                    <th style="text-align: center; padding: 1rem; font-weight: 600; color: #333;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr style="border-bottom: 1px solid #dee2e6; transition: background-color 0.3s ease;">
                        <td style="padding: 1rem;">
                            <strong style="color: #333;">{{ $task->title }}</strong>
                            @if($task->description)
                                <p style="margin: 0.5rem 0 0; font-size: 0.9rem; color: #666;">{{ Str::limit($task->description, 60) }}</p>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #666;">{{ $task->user->name }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span class="status-badge" style="
                                @if($task->status === 'pending')
                                    background: #fff3cd; color: #856404;
                                @elseif($task->status === 'in_progress')
                                    background: #cce5ff; color: #004085;
                                @else
                                    background: #d4edda; color: #155724;
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center; color: #667eea; font-weight: 600;">
                            {{ $task->comments->count() }}
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-info btn-small" style="text-decoration: none;">Edit</a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Delete this task?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 3rem; text-align: center; color: #999;">
                            No tasks yet. <a href="{{ route('tasks.create') }}" style="color: #667eea; text-decoration: none;">Create one</a> to get started! 📝
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $tasks->links() }}
    </div>

    <div style="text-align: center; margin-top: 1.5rem; color: #666; font-size: 0.9rem;">
        Showing {{ $tasks->firstItem() ?? 0 }} to {{ $tasks->lastItem() ?? 0 }} of {{ $tasks->total() }} tasks
    </div>
</div>
@endsection
