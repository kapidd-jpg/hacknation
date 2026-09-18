@extends('layout.dashboard')

@section('title', 'Notifikasi - PintarKuy')

@section('pageContent')
    <div class="flex flex-col gap-6">
        <div class="dash-head dash-reveal">
            <div>
                <h1>Notifikasi</h1>
                <p class="dash-head-sub">Semua pemberitahuan aktivitas belajar kamu.</p>
            </div>
        </div>

        <div class="dash-card dash-reveal notif-page">
            @php
                $notifications = $notifications ?? collect();
            @endphp

            @if ($notifications->isEmpty())
                <div class="dash-notif-empty" style="padding:44px 24px;">Belum ada notifikasi.</div>
            @else
                <div class="dash-notif-list" style="max-height:none;">
                    @foreach ($notifications as $n)
                        @php
                            $nData = is_array($n->data) ? $n->data : [];
                            $nIcon = $nData['icon'] ?? $n->type;
                            $nUrl = $nData['url'] ?? null;
                        @endphp
                        <a href="{{ $nUrl ?: '#' }}" class="dash-notif-item{{ $n->isUnread() ? ' dash-notif-item--unread' : '' }}">
                            <svg class="dash-notif-ico" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ is_string($nIcon) && $nIcon === 'paket' ? 'M12 2v20m6-16H8a4 4 0 100 8h8a4 4 0 100 8H6' : ($nIcon === 'latsol' ? 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125' : ($nIcon === 'materi' ? 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' : 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z')) }}"/></svg>
                            <div class="dash-notif-item-body">
                                <p class="dash-notif-item-title">{{ $n->title }}</p>
                                @if ($n->body)
                                    <p class="dash-notif-item-sub">{{ $n->body }}</p>
                                @endif
                                <span class="dash-notif-item-time">{{ $n->created_at ? $n->created_at->diffForHumans() : '' }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if (method_exists($notifications, 'links'))
                    <div class="notif-page-nav">{{ $notifications->links() }}</div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard/site.css'])
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.dash-reveal').forEach((el) => el.classList.add('is-visible'));
    </script>
@endpush