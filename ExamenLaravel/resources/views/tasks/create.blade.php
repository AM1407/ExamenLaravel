@extends('layouts.app')

@section('title', 'Create New Task')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <h2>Create New Task</h2>
    <p style="color: #666; margin-bottom: 2rem;">Fill in the details below to create a new task for your team.</p>

    <div style="background: white; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Task Title *</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g., Fix login button on mobile">
                @error('title')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Add more details about this task..." style="min-height: 120px;">{{ old('description') }}</textarea>
                @error('description')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Assign To *</label>
                <select name="user_id" required>
                    <option value="">-- Select a team member --</option>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">Create Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
