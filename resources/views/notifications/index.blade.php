<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications | ANI-CARE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f7f6;
            font-family: "Segoe UI", sans-serif;
        }

        .topbar {
            background: #198754;
            color: #fff;
            padding: 14px 16px;
        }

        .wrap {
            width: min(850px, 100%);
            margin: 0 auto;
            padding: 22px 14px 60px;
        }

        .notif-card {
            display: flex;
            gap: 12px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #e5ebe8;
            border-radius: 15px;
            background: #fff;
            text-decoration: none;
            color: inherit;
        }

        .notif-card.unread {
            border-left: 5px solid #198754;
            background: #f1fbf5;
        }

        .notif-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: #eaf7f0;
            color: #198754;
            font-size: 20px;
        }

        .notif-title {
            font-weight: 800;
        }

        .notif-message {
            margin-top: 3px;
            color: #697078;
            font-size: 13px;
        }

        .notif-time {
            margin-top: 5px;
            color: #98a0a6;
            font-size: 11px;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="container-fluid d-flex align-items-center justify-content-between gap-2">
        <strong>
            <i class="bi bi-bell-fill me-1"></i>
            ANI-CARE Notifications
        </strong>

        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<main class="wrap">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h3 class="fw-bold text-success mb-1">Notifications</h3>
            <div class="text-muted small">
                {{ $unreadCount }} unread notification{{ $unreadCount === 1 ? '' : 's' }}
            </div>
        </div>

        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button class="btn btn-outline-success btn-sm">
                Mark All Read
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($notifications as $notification)
        <a
            href="{{ route('notifications.open', $notification->id) }}"
            class="notif-card {{ !$notification->is_read ? 'unread' : '' }}"
        >
            <div class="notif-icon">
                <i class="bi {{ $notification->icon ?: 'bi-bell-fill' }}"></i>
            </div>

            <div>
                <div class="notif-title">
                    {{ $notification->title }}
                </div>

                @if($notification->message)
                    <div class="notif-message">
                        {{ $notification->message }}
                    </div>
                @endif

                <div class="notif-time">
                    {{ $notification->created_at?->format('M d, Y h:i A') }}
                    ·
                    {{ $notification->created_at?->diffForHumans() }}
                </div>
            </div>
        </a>
    @empty
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body py-5 text-center text-muted">
                <i class="bi bi-check-circle display-5 text-success"></i>
                <div class="mt-3">No notifications yet.</div>
            </div>
        </div>
    @endforelse

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</main>

</body>
</html>
