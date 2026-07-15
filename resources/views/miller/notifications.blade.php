@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <h3>Notifications</h3>

    <div class="list-group">
      @forelse($notifications as $n)
        <div class="list-group-item d-flex justify-content-between align-items-start {{ $n->read_at ? 'bg-light text-muted' : '' }}">
          <div>
            <div class="fw-bold">{{ $n->data['title'] ?? ucfirst($n->type) }}</div>
            <div class="small">{{ $n->data['message'] ?? '' }}</div>
            <div class="small text-muted">{{ $n->created_at->diffForHumans() }}</div>
          </div>
          <div>
            @unless($n->read_at)
              <form method="POST" action="{{ route('miller.notifications.read', $n->id) }}">
                @csrf
                <button class="btn btn-sm btn-outline-primary">Mark as read</button>
              </form>
            @endunless
          </div>
        </div>
      @empty
        <div class="list-group-item">No notifications.</div>
      @endforelse
    </div>

    <div class="mt-3">{{ $notifications->links() }}</div>
  </div>
@endsection
