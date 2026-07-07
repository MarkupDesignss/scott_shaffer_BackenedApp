@extends('layouts.admin')

@section('content')
<style>
    .ip-page { padding: 2rem 0; font-family: inherit; }
    .ip-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; }
    .ip-title { font-size: 22px; font-weight: 500; color: #0C447C; margin: 0 0 4px; }
    .ip-subtitle { font-size: 13px; color: #378ADD; margin: 0; }
    .ip-back-btn { font-size: 13px; padding: 8px 16px; border-radius: 8px; border: 1px solid #378ADD; color: #185FA5; background: #E6F1FB; text-decoration: none; white-space: nowrap; transition: background 0.15s; }
    .ip-back-btn:hover { background: #B5D4F4; color: #0C447C; text-decoration: none; }

    .ip-stat-card { background: #E6F1FB; border-radius: 12px; border: 1px solid #B5D4F4; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 14px; }
    .ip-stat-icon { width: 42px; height: 42px; background: #B5D4F4; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ip-stat-icon svg { width: 20px; height: 20px; stroke: #0C447C; fill: none; stroke-width: 1.8; }
    .ip-stat-label { font-size: 11px; color: #185FA5; font-weight: 600; margin: 0 0 2px; text-transform: uppercase; letter-spacing: 0.06em; }
    .ip-stat-val { font-size: 26px; font-weight: 500; color: #0C447C; margin: 0; line-height: 1; }
    .ip-stat-note { margin-left: auto; font-size: 12px; color: #378ADD; max-width: 260px; text-align: right; }
    .ip-stat-note code { font-size: 11px; background: #B5D4F4; color: #042C53; padding: 1px 5px; border-radius: 4px; }

    .ip-cat-card { background: #fff; border: 1px solid #B5D4F4; border-radius: 12px; margin-bottom: 1.25rem; overflow: hidden; }
    .ip-cat-header { background: #185FA5; padding: 10px 18px; display: flex; align-items: center; gap: 10px; }
    .ip-cat-name { font-size: 14px; font-weight: 500; color: #E6F1FB; margin: 0; }
    .ip-cat-badge { font-size: 11px; background: #0C447C; color: #B5D4F4; padding: 2px 9px; border-radius: 99px; margin-left: auto; }
    .ip-cat-body { padding: 1.25rem 1.25rem 0.5rem; }

    .ip-list-section { margin-bottom: 1.25rem; }
    .ip-list-title { font-size: 14px; font-weight: 500; color: #0C447C; margin: 0 0 10px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .ip-list-id-pill { font-size: 11px; font-weight: 400; color: #185FA5; background: #E6F1FB; border: 1px solid #B5D4F4; padding: 2px 8px; border-radius: 99px; }
    .ip-divider { height: 1px; background: #B5D4F4; margin: 1rem 0; opacity: 0.4; }

    .ip-table-wrap { overflow-x: auto; border-radius: 8px; border: 1px solid #B5D4F4; }
    .ip-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .ip-table thead tr { background: #E6F1FB; }
    .ip-table th { text-align: left; padding: 8px 12px; font-size: 11px; font-weight: 600; color: #185FA5; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #B5D4F4; }
    .ip-table td { padding: 10px 12px; color: #1a1a1a; border-bottom: 1px solid #dbeeff; vertical-align: top; }
    .ip-table tbody tr:last-child td { border-bottom: none; }
    .ip-table tbody tr:hover td { background: #f0f8ff; }
    .ip-id-cell { font-size: 12px; color: #378ADD; font-family: monospace; }
    .ip-custom-text { color: #888; font-style: italic; }

    .ip-user-badge { display: inline-block; background: #E6F1FB; border: 1px solid #B5D4F4; border-radius: 6px; padding: 5px 9px; margin: 2px 3px 2px 0; }
    .ip-user-name { font-size: 12px; font-weight: 600; color: #0C447C; margin: 0; }
    .ip-user-email { font-size: 11px; color: #378ADD; margin: 0; }
    .ip-user-pos { font-size: 11px; color: #185FA5; margin: 2px 0 0; }
    .ip-no-pref { font-size: 12px; color: #aaa; font-style: italic; }

    .ip-reorder-pill { display: inline-flex; align-items: center; font-size: 12px; font-weight: 600; color: #0C447C; background: #B5D4F4; padding: 3px 10px; border-radius: 99px; }
    .ip-empty-alert { background: #fff8e1; border: 1px solid #ffe082; color: #795548; border-radius: 8px; padding: 0.75rem 1rem; font-size: 13px; }
    .ip-no-items { font-size: 13px; color: #aaa; font-style: italic; }
</style>

<div class="container-fluid ip-page">

    {{-- Header --}}
    <div class="ip-header">
        <div>
            <h1 class="ip-title">Interest preferences — {{ $interestName }}</h1>
            <p class="ip-subtitle">Manage and review user-defined ordering per list</p>
        </div>
        <a href="{{ route('admin.interest.index') }}" class="ip-back-btn">← Back to interests</a>
    </div>

    {{-- Stat card --}}
    <!--<div class="ip-stat-card">-->
    <!--    <div class="ip-stat-icon">-->
    <!--        <svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h10M4 18h6"/></svg>-->
    <!--    </div>-->
    <!--    <div>-->
    <!--        <p class="ip-stat-label">Total reorders</p>-->
    <!--        <p class="ip-stat-val">{{ $totalReorders ?? 0 }}</p>-->
    <!--    </div>-->
    <!--    <div class="ip-stat-note">-->
    <!--        Sum of <code>position_updated_count</code> across all list items-->
    <!--    </div>-->
    <!--</div>-->

    {{-- Categories --}}
    @forelse($data as $category)
        <div class="ip-cat-card">
            <div class="ip-cat-header">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#B5D4F4" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span class="ip-cat-name">{{ $category['category_name'] }}</span>
                <span class="ip-cat-badge">{{ count($category['lists']) }} {{ Str::plural('list', count($category['lists'])) }}</span>
            </div>

            <div class="ip-cat-body">
                @foreach ($category['lists'] as $index => $list)
                    @if ($index > 0)
                        <div class="ip-divider"></div>
                    @endif

                    <div class="ip-list-section">
                        <p class="ip-list-title">
                            {{ $list['list_title'] }}
                            <span class="ip-list-id-pill">ID: {{ $list['id'] }}</span>
                        </p>

                        @if ($list['items']->isEmpty())
                            <p class="ip-no-items">No active items</p>
                        @else
                            <div class="ip-table-wrap">
                                <table class="ip-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Custom text</th>
                                            <th>User positions</th>
                                            <th>Reorders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list['items'] as $item)
                                            <tr>
                                                <td class="ip-id-cell">#{{ $item['id'] }}</td>
                                                <td>{{ $item['name'] }}</td>
                                                <td>
                                                    @if (!empty($item['custom_text']))
                                                        {{ $item['custom_text'] }}
                                                    @else
                                                        <span class="ip-custom-text">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $positions = $item['user_positions'] ?? [];
                                                    @endphp

                                                    @if (!empty($positions))
                                                        @foreach ($positions as $uid => $pos)
                                                            @php $user = $userMap[$uid] ?? null; @endphp
                                                            <div class="ip-user-badge">
                                                                <p class="ip-user-name">{{ $user['name'] ?? 'User ' }}</p>
                                                                <p class="ip-user-email">{{ $user['email'] ?? '' }}</p>
                                                                <p class="ip-user-pos">→ pos {{ $pos }}</p>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="ip-no-pref">No preferences</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="ip-reorder-pill">{{ $item['position_updated_count'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="ip-empty-alert">No categories found for this interest.</div>
    @endforelse

</div>
@endsection