@extends('layouts.app')

@section('header')
    @include('partials.stat_nav')
@endsection

@section('content')

<style>
    .filter-row{margin-bottom:15px;padding:10px 12px;background:rgba(255,255,255,.9);border:1px solid #b9a37a;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
    .filter-row select{padding:6px 8px;border-radius:4px;border:1px solid #b9a37a;font-family:'Georgia',serif}
    .filter-row button{padding:6px 12px;border-radius:4px;border:1px solid #3a2e1e;background:#3a2e1e;color:#fff;cursor:pointer}
    .stats-table{width:100%;border-collapse:collapse;background:rgba(255,255,255,.9);margin-top:10px;color: #333;}
    .stats-table th,.stats-table td{border:1px solid #b9a37a;padding:6px 8px;font-size:13px;vertical-align:top}
    .stats-table th{background:#e8d7b9;position:sticky;top:0;z-index:2}
    .sticky-col{position:sticky;left:0;background:rgba(255,255,255,.98);z-index:3;min-width:220px}
    .sticky-col-2{position:sticky;left:220px;background:rgba(255,255,255,.98);z-index:3;min-width:120px}
    .cell-total{font-weight:700}
    .cell-sub{font-size:12px;opacity:.85}
    tfoot td{background:#f5ebd7;font-weight:700}

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

<h2>📊 Statystyki klasy – kategorie punktów</h2>

<div class="filter-row">
    <form method="GET" action="{{ route('statistics.class.categories') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <label>Klasa:</label>
        <select name="class_id" required>
            <option value="">— wybierz —</option>
            @foreach($classes as $c)
                <option value="{{ $c->class_id }}" {{ (string)$classId === (string)$c->class_id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Pokaż</button>
    </form>
</div>

@if(!$classId)
    <p>Wybierz klasę, aby zobaczyć tabelę.</p>
@elseif($students->isEmpty())
    <p>W tej klasie nie znaleziono uczniów.</p>
@else
    <div style="overflow:auto;border:1px solid #b9a37a;">
        <table class="stats-table">
            <thead>
                <tr>
                    <th class="sticky-col">Uczeń</th>
                    <th class="sticky-col-2">Suma okresu</th>
                    @foreach($categories as $cat)
                        <th style="min-width:140px;">
                            {{ $cat->name }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($students as $s)
                    @php $rt = $rowTotals[$s->user_id] ?? ['total'=>0,'plus'=>0,'minus'=>0]; @endphp
                    <tr>
                        <td class="sticky-col">
                            {{ $s->surname }} {{ $s->name }}<br/>
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

                        <td class="sticky-col-2">
                            <div class="cell-total">{{ $rt['total'] }}</div>
                            <div class="cell-sub">+{{ $rt['plus'] }} / {{ $rt['minus'] }}</div>
                        </td>

                        @foreach($categories as $cat)
                            @php
                                $cell = $pivot[$s->user_id][$cat->point_category_id] ?? ['total'=>0,'plus'=>0,'minus'=>0];
                            @endphp
                            <td>
                                <div class="cell-total">{{ $cell['total'] }}</div>
                                <div class="cell-sub">+{{ $cell['plus'] }} / {{ $cell['minus'] }}</div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td class="sticky-col">Suma kategorii</td>
                    @php
                        $grandT = $grandP = $grandM = 0;
                        foreach($colTotals as $ct){ $grandT += $ct['total']; $grandP += $ct['plus']; $grandM += $ct['minus']; }
                    @endphp
                    <td class="sticky-col-2">
                        <div class="cell-total">{{ $grandT }}</div>
                        <div class="cell-sub">+{{ $grandP }} / {{ $grandM }}</div>
                    </td>

                    @foreach($categories as $cat)
                        @php $ct = $colTotals[$cat->point_category_id] ?? ['total'=>0,'plus'=>0,'minus'=>0]; @endphp
                        <td>
                            <div class="cell-total">{{ $ct['total'] }}</div>
                            <div class="cell-sub">+{{ $ct['plus'] }} / {{ $ct['minus'] }}</div>
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
@endif
@endsection