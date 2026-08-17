@php
    $aniBellNotifications = collect();
    $aniBellUnreadCount = 0;

    if (
        auth()->check() &&
        \Illuminate\Support\Facades\Schema::hasTable('in_app_notifications')
    ) {
        $aniBellNotifications = \App\Models\InAppNotification::query()
            ->where('user_id', auth()->id())
            ->latest('created_at')
            ->take(8)
            ->get();

        $aniBellUnreadCount = \App\Models\InAppNotification::query()
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }
@endphp

<style>
    .ani-notif-wrap {
        position: relative;
        flex: 0 0 auto;
    }

    .ani-notif-btn {
        position: relative;
        width: 42px !important;
        min-width: 42px !important;
        height: 42px;
        min-height: 42px;
        padding: 0 !important;
        border: 0;
        border-radius: 12px;
        background: rgba(255,255,255,.18);
        color: #fff;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .ani-notif-btn:hover,
    .ani-notif-btn:focus {
        background: #fff;
        color: #198754;
    }

    .ani-notif-btn.has-unread i {
        animation: aniBellRing 1.7s ease-in-out infinite;
        transform-origin: top center;
    }

    .ani-notif-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        min-width: 20px;
        height: 20px;
        padding: 0 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #198754;
        border-radius: 999px;
        background: #dc3545;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        line-height: 1;
    }

    .ani-notif-menu {
        width: min(370px, calc(100vw - 20px));
        max-height: min(540px, calc(100vh - 90px));
        overflow-y: auto;
        padding: 0;
        border: 0;
        border-radius: 16px;
        box-shadow: 0 16px 45px rgba(0,0,0,.2);
    }

    .ani-notif-head {
        position: sticky;
        top: 0;
        z-index: 2;
        padding: 13px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: #fff;
        border-bottom: 1px solid #edf0f2;
    }

    .ani-notif-head strong {
        color: #20252b;
        font-size: 15px;
    }

    .ani-notif-count {
        color: #dc3545;
        font-size: 12px;
        font-weight: 800;
    }

    .ani-notif-item {
        display: flex;
        gap: 10px;
        padding: 12px 14px;
        color: #20252b;
        text-decoration: none;
        background: #fff;
        border-bottom: 1px solid #f0f2f4;
    }

    .ani-notif-item:hover {
        background: #f8fbf9;
        color: #20252b;
    }

    .ani-notif-item.unread {
        background: #eefaf3;
    }

    .ani-notif-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: #eaf7f0;
        color: #198754;
        font-size: 18px;
    }

    .ani-notif-content {
        min-width: 0;
        flex: 1;
    }

    .ani-notif-title {
        color: #20252b;
        font-size: 13px;
        font-weight: 800;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .ani-notif-message {
        margin-top: 3px;
        color: #697078;
        font-size: 12px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .ani-notif-time {
        margin-top: 4px;
        color: #98a0a6;
        font-size: 10px;
    }

    .ani-notif-empty {
        padding: 22px 16px;
        color: #697078;
        text-align: center;
        font-size: 13px;
        background: #fff;
    }

    .ani-notif-footer {
        position: sticky;
        bottom: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: #fff;
        border-top: 1px solid #edf0f2;
    }

    .ani-notif-footer a,
    .ani-notif-footer button {
        min-height: 42px;
        border: 0;
        background: #fff;
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 12px;
        font-weight: 800;
    }

    .ani-notif-footer a:hover,
    .ani-notif-footer button:hover {
        background: #eaf7f0;
    }

    @keyframes aniBellRing {
        0%, 65%, 100% { transform: rotate(0); }
        70% { transform: rotate(12deg); }
        75% { transform: rotate(-10deg); }
        80% { transform: rotate(8deg); }
        85% { transform: rotate(-6deg); }
        90% { transform: rotate(0); }
    }

    @media (max-width: 600px) {
        .ani-notif-menu {
            position: fixed !important;
            top: 66px !important;
            left: 10px !important;
            right: 10px !important;
            width: auto !important;
            transform: none !important;
        }
    }
</style>

<div class="dropdown ani-notif-wrap">
    <button
        type="button"
        class="ani-notif-btn {{ $aniBellUnreadCount > 0 ? 'has-unread' : '' }}"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false"
        title="Notifications"
        aria-label="Notifications"
    >
        <i class="bi {{ $aniBellUnreadCount > 0 ? 'bi-bell-fill' : 'bi-bell' }}"></i>

        @if($aniBellUnreadCount > 0)
            <span class="ani-notif-badge">
                {{ $aniBellUnreadCount > 99 ? '99+' : $aniBellUnreadCount }}
            </span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end ani-notif-menu">
        <div class="ani-notif-head">
            <strong>Notifications</strong>

            @if($aniBellUnreadCount > 0)
                <span class="ani-notif-count">
                    {{ $aniBellUnreadCount }} unread
                </span>
            @else
                <span class="text-muted small">All caught up</span>
            @endif
        </div>

        @forelse($aniBellNotifications as $aniNotification)
            <a
                href="{{ route('notifications.open', $aniNotification->id) }}"
                class="ani-notif-item {{ !$aniNotification->is_read ? 'unread' : '' }}"
            >
                <div class="ani-notif-icon">
                    <i class="bi {{ $aniNotification->icon ?: 'bi-bell-fill' }}"></i>
                </div>

                <div class="ani-notif-content">
                    <div class="ani-notif-title">
                        {{ $aniNotification->title }}
                    </div>

                    @if(!empty($aniNotification->message))
                        <div class="ani-notif-message">
                            {{ \Illuminate\Support\Str::limit($aniNotification->message, 120) }}
                        </div>
                    @endif

                    <div class="ani-notif-time">
                        {{ $aniNotification->created_at?->diffForHumans() }}
                    </div>
                </div>
            </a>
        @empty
            <div class="ani-notif-empty">
                <i class="bi bi-check-circle fs-4 d-block mb-2 text-success"></i>
                No notifications yet.
            </div>
        @endforelse

        <div class="ani-notif-footer">
            <a href="{{ route('notifications.index') }}">
                View All
            </a>

            <form
                method="POST"
                action="{{ route('notifications.readAll') }}"
                class="m-0"
            >
                @csrf

                <button type="submit">
                    Mark All Read
                </button>
            </form>
        </div>
    </div>
</div>
