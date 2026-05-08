@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 2rem;">
    <h1>Task Manager</h1>

    @if(session('success'))
        <div style="padding: 1rem; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create Task Form -->
    <form action="{{ route('tasks.store') }}" method="POST" style="margin-bottom: 2rem; background: #f8f9fa; padding: 1.5rem; border-radius: 8px;">
        @csrf
        <h3>Create New Task</h3>

        <div style="margin-bottom: 1rem;">
            <label>Title *</label>
            <input type="text" name="title" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            @error('title') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Description</label>
            <textarea name="description" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; min-height: 80px;"></textarea>
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Assign To *</label>
            <select name="user_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" style="padding: 0.5rem 1rem; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Create Task</button>
    </form>

    <!-- Tasks Table -->
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd;">
                <th style="text-align: left; padding: 1rem;">Task</th>
                <th style="text-align: left; padding: 1rem;">Assigned To</th>
                <th style="text-align: left; padding: 1rem;">Status</th>
                <th style="text-align: center; padding: 1rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 1rem;">
                        <strong>{{ $task->title }}</strong>
                        @if($task->description)
                            <p style="margin: 0.5rem 0 0; font-size: 0.9rem; color: #666;">{{ Str::limit($task->description, 60) }}</p>
                        @endif
                    </td>
                    <td style="padding: 1rem;">{{ $task->user->name }}</td>
                    <td style="padding: 1rem;">
                        <form action="{{ route('tasks.update', $task) }}" method="POST" style="display: inline;">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="padding: 0.4rem; border-radius: 4px; border: 1px solid #ddd;">
                                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in-progress" {{ $task->status === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <!-- Hidden inputs to preserve other fields -->
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="description" value="{{ $task->description }}">
                            <input type="hidden" name="user_id" value="{{ $task->user_id }}">
                        </form>
                    </td>
                    <td style="text-align: center; padding: 1rem;">
                        <a href="{{ route('tasks.edit', $task) }}" style="padding: 0.4rem 0.8rem; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="padding: 0.4rem 0.8rem; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
