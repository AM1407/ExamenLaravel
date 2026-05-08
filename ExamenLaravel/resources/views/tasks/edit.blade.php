@extends('layouts.app')

@section('content')
<div style="max-width: 500px; margin: 2rem auto; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
    <h2>Edit Task</h2>

    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf @method('PUT')

        <div style="margin-bottom: 1rem;">
            <label>Title *</label>
            <input type="text" name="title" value="{{ $task->title }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Description</label>
            <textarea name="description" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; min-height: 80px;">{{ $task->description }}</textarea>
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Status *</label>
            <select name="status" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in-progress" {{ $task->status === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
            </select>
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Assign To *</label>
            <select name="user_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}" {{ $task->user_id === $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" style="padding: 0.5rem 1rem; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Update Task</button>
        <a href="{{ route('tasks.index') }}" style="padding: 0.5rem 1rem; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">Cancel</a>
    </form>
</div>
@endsection
