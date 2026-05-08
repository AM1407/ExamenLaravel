@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0;">{{ $task->title }}</h2>
        <a href="{{ route('tasks.index') }}" style="color: #667eea; text-decoration: none;">← Back to Tasks</a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div>
            <div style="background: white; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 2rem;">
                <h3>Task Details</h3>

                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" required value="{{ old('title', $task->title) }}">
                        @error('title')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" style="min-height: 150px;">{{ old('description', $task->description) }}</textarea>
                        @error('description')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" required>
                                <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>Assign To *</label>
                            <select name="user_id" required>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $task->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <div style="background: white; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0;">Comments ({{ $task->comments->count() }})</h3>

                @forelse($task->comments as $comment)
                    <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <strong style="color: #333;">{{ $comment->user->name }}</strong>
                            <span style="font-size: 0.85rem; color: #999;">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="margin: 0; color: #555;">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p style="color: #999; font-style: italic;">No comments yet. Add one to keep the team updated!</p>
                @endforelse
            </div>
        </div>

        <div>
            <div style="background: white; border-radius: 8px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 1.5rem;">
                <h4 style="margin-top: 0; color: #333;">Status</h4>
                <div style="
                    padding: 1rem;
                    border-radius: 6px;
                    text-align: center;
                    font-weight: 600;
                    @if($task->status === 'pending')
                        background: #fff3cd; color: #856404;
                    @elseif($task->status === 'in_progress')
                        background: #cce5ff; color: #004085;
                    @else
                        background: #d4edda; color: #155724;
                    @endif
                ">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </div>
            </div>

            @if($task->started_at)
                <div style="background: white; border-radius: 8px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 1.5rem; border-left: 4px solid #667eea;">
                    <h4 style="margin-top: 0; color: #333;">⏱️ Time Tracking</h4>

                    <div style="font-size: 0.9rem; color: #666;">
                        <p style="margin: 0.5rem 0;">
                            <strong>Total Time:</strong>
                            @if($task->total_hours !== null)
                                {{ $task->total_hours }} hours
                            @else
                                N/A
                            @endif
                        </p>

                        <p style="margin: 0.5rem 0;">
                            <strong>Duration:</strong>
                            {{ $task->time_elapsed ?? 'In progress' }}
                        </p>

                        @if($task->started_at)
                            <p style="margin: 0.5rem 0;">
                                <strong>Started:</strong>
                                {{ $task->formatted_started_date }}
                            </p>
                        @endif

                        @if($task->completed_at)
                            <p style="margin: 0.5rem 0;">
                                <strong>Completed:</strong>
                                {{ $task->formatted_completed_date }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <div style="background: white; border-radius: 8px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 1.5rem;">
                <h4 style="margin-top: 0; color: #333;">👤 Assigned To</h4>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; text-align: center;">
                    <strong style="color: #333;">{{ $task->user->name }}</strong>
                    <p style="margin: 0.5rem 0 0; font-size: 0.9rem; color: #666;">{{ $task->user->email }}</p>
                </div>
            </div>

            <div style="background: white; border-radius: 8px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 1.5rem;">
                <h4 style="margin-top: 0; color: #333;">📅 Dates</h4>
                <div style="font-size: 0.9rem; color: #666;">
                    <p style="margin: 0.5rem 0;">
                        <strong>Created:</strong>
                        <br>
                        {{ $task->formatted_created_date }}
                        <br>
                        <span style="color: #999;">({{ $task->days_since_created }} days ago)</span>
                    </p>

                    <p style="margin: 0.5rem 0;">
                        <strong>Last Updated:</strong>
                        <br>
                        {{ $task->updated_at->format('M d, Y \a\t H:i') }}
                        <br>
                        <span style="color: #999;">({{ $task->updated_at->diffForHumans() }})</span>
                    </p>
                </div>
            </div>

            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="width: 100%;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width: 100%; text-align: center;" onclick="return confirm('Are you sure you want to delete this task? This action cannot be undone.')">
                    Delete Task
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
