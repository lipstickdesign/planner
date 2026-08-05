<!DOCTYPE html>
<html lang="nb">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kommende hjemmekamper – {{ $company->name }}</title>
<style>
  :root{ --ink:#1c2733; --soft:#6b7885; --line:#e7ecf2; --accent:#2f6fd6; --card:#ffffff; }
  *{box-sizing:border-box}
  body{margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:transparent;padding:8px}
  .feed{max-width:640px;margin:0 auto}
  .daygrp{margin-bottom:18px}
  .dayhead{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--accent);margin:0 0 8px;padding-bottom:5px;border-bottom:2px solid var(--line)}
  .match{display:flex;gap:12px;align-items:flex-start;padding:10px 4px;border-bottom:1px solid var(--line)}
  .match:last-child{border-bottom:0}
  .time{flex:none;width:52px;font-weight:700;font-variant-numeric:tabular-nums}
  .info{flex:1;min-width:0}
  .teams{font-weight:600;line-height:1.3}
  .vs{color:var(--soft);font-weight:400;margin:0 4px}
  .meta{font-size:12.5px;color:var(--soft);margin-top:2px}
  .badge{display:inline-block;font-size:11px;font-weight:700;color:#b23535;background:#f6e0e0;border-radius:6px;padding:1px 6px;margin-left:6px}
  .empty{color:var(--soft);text-align:center;padding:28px 10px;font-size:15px}
  .foot{margin-top:14px;font-size:11px;color:#9aa6b2;text-align:center}
</style>
</head>
<body>
<div class="feed">
@php
  use Carbon\Carbon;
  $grouped = collect($kamper)->groupBy('date');
@endphp

@if($grouped->isEmpty())
  <div class="empty">Ingen hjemmekamper de neste sju dagene.</div>
@else
  @foreach($grouped as $date => $matches)
    @php
      $head = $date ? ucfirst(Carbon::parse($date)->locale('nb')->isoFormat('dddd D. MMMM')) : '';
    @endphp
    <div class="daygrp">
      <div class="dayhead">{{ $head }}</div>
      @foreach($matches as $m)
        <div class="match">
          <div class="time">{{ $m['time'] ?? '' }}</div>
          <div class="info">
            <div class="teams">{{ $m['home_team'] }}<span class="vs">–</span>{{ $m['away_team'] }}
              @if(!empty($m['note']))<span class="badge">{{ $m['note'] }}</span>@endif
            </div>
            <div class="meta">
              @if(!empty($m['tournament'])){{ $m['tournament'] }}@endif
              @if(!empty($m['location'])) · {{ $m['location'] }}@endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endforeach
@endif

  <div class="foot">Oppdateres automatisk · {{ $company->name }}</div>
</div>
</body>
</html>
