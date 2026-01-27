@extends('layouts.app')

@section('header')
    @include('partials.stat_nav')
@endsection

@section('content')
<style>
    .filter-row {
        margin-bottom: 15px;
        padding: 10px 12px;
        background: rgba(255,255,255,0.9);
        border: 1px solid #b9a37a;
        display:flex;
        gap:10px;
        align-items:center;
        flex-wrap:wrap;
    }
    .filter-row select {
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #b9a37a;
        font-family: 'Georgia', serif;
    }
    .filter-row button {
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #3a2e1e;
        background: #3a2e1e;
        color: #fff;
        cursor:pointer;
    }
    .stats-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255,255,255,0.9);
        margin-top: 10px;
        color: #333;
    }
    .stats-table th, .stats-table td {
        border: 1px solid #b9a37a;
        padding: 6px 8px;
        font-size: 13px;
        vertical-align: top;
    }
    .stats-table th {
        background: #e8d7b9;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .cell-total { font-weight: bold; }
    .cell-sub { font-size: 12px; opacity: .85; }
    .sticky-col {
        position: sticky;
        left: 0;
        background: rgba(255,255,255,0.98);
        z-index: 3;
    }
    .sticky-col-2 {
        position: sticky;
        left: 120px;
        background: rgba(255,255,255,0.98);
        z-index: 3;
    }

    .house-badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
        color: #fff;
        white-space: nowrap;
    }
    .house-gryffindor { background:#7f0909; }
    .house-slytherin  { background:#0d6217; }
    .house-ravenclaw  { background:#0e1a40; }
    .house-hufflepuff { background:#eee117; color:#000; }
</style>

<h2>📊 Statystyki klasy – punkty dzienne</h2>

<div class="filter-row">
    <form method="GET" action="{{ route('statistics.class.daily') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <label for="class_id">Klasa:</label>
        <select name="class_id" id="class_id" required>
            <option value="">— wybierz klasę —</option>
            @foreach($classes as $c)
                <option value="{{ $c->class_id }}" {{ (string)$classId === (string)$c->class_id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <label for="days">Zakres:</label>
        <select name="days" id="days">
            <option value="7"  {{ (int)$daysParam === 7 ? 'selected' : '' }}>7 dni</option>
            <option value="14" {{ (int)$daysParam === 14 ? 'selected' : '' }}>14 dni</option>
            <option value="30" {{ (int)$daysParam === 30 ? 'selected' : '' }}>30 dni</option>
        </select>

        <button type="submit">Pokaż</button>
    </form>
</div>

@if(!$classId)
    <p>Wybierz klasę, aby zobaczyć tabelę.</p>
@elseif($students->isEmpty())
    <p>W tej klasie nie znaleziono uczniów.</p>
@else
    <div style="overflow:auto; border:1px solid #b9a37a;">
        <table class="stats-table">
            <thead>
                <tr>
                    <th class="sticky-col" style="min-width:120px;">Suma okresu</th>
                    <th class="sticky-col-2" style="min-width:180px;">Uczeń</th>
                    @foreach($days as $day)
                        <th style="min-width:110px;">
                            {{ \Carbon\Carbon::parse($day)->format('d.m') }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($students as $s)
                    @php
                        $t = $totals[$s->user_id] ?? ['total'=>0,'plus'=>0,'minus'=>0];
                    @endphp
                    <tr>
                        <td class="sticky-col">
                            <div class="cell-total">{{ $t['total'] }}</div>
                            <div class="cell-sub">+{{ $t['plus'] }} / {{ $t['minus'] }}</div>
                        </td>
                        <td class="sticky-col-2">
                            {{ $s->surname }} {{ $s->name }}</br>
                            @php
                                $houseName = $s->house->name ?? null;
                                $houseClass = match($houseName) {
                                    'Gryffindor' => 'house-badge house-gryffindor',
                                    'Slytherin'  => 'house-badge house-slytherin',
                                    'Ravenclaw' => 'house-badge house-ravenclaw',
                                    'Hufflepuff'=> 'house-badge house-hufflepuff',
                                    default     => 'house-badge',
                                };
                            @endphp
                            @if($houseName)
                                <span class="{{ $houseClass }}">{{ $houseName }}</span>
                            @else
                                —
                            @endif
                        </td>

                        @foreach($days as $day)
                            @php
                                $cell = $pivot[$s->user_id][$day] ?? ['total'=>0,'plus'=>0,'minus'=>0];
                            @endphp
                            <td>
                                <div class="cell-total">{{ $cell['total'] }}</div>
                                <div class="cell-sub">+{{ $cell['plus'] }} / {{ $cell['minus'] }}</div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
