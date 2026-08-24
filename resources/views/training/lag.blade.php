<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<title>Treningstider · Lag</title>
<style>
  :root{--bg:#f3f5fb;--card:#fff;--ink:#1a1f33;--ink-soft:#5b6b86;--line:#e6eaf2;--flik:#2f6fd6;--grey:#8795a3;--radius:16px}
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:var(--ink);font-family:'Ubuntu',system-ui,sans-serif;font-size:14px;line-height:1.55}
  .wrap{max-width:1100px;margin:0 auto;padding:26px 20px 70px}
  a{color:var(--flik);text-decoration:none}
  a:hover{text-decoration:underline}
  h1{font-size:23px;font-weight:700;margin:6px 0 2px}
  .sub{color:var(--ink-soft);font-size:13px;margin:0 0 16px}
  .subnav{display:flex;gap:6px;margin:14px 0 20px}
  .subnav a{padding:7px 14px;border-radius:9px;border:1px solid var(--line);background:var(--card);color:var(--ink-soft);font-weight:500}
  .subnav a.active{border-color:var(--flik);color:var(--flik);background:#eef4fd}
  h2{font-size:15px;font-weight:700;margin:22px 0 8px;display:flex;align-items:center;gap:8px}
  .idchip{display:inline-block;width:11px;height:11px;border-radius:3px}
  .card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden}
  table{border-collapse:collapse;width:100%;font-size:13px}
  th,td{text-align:left;padding:9px 12px;border-bottom:1px solid var(--line)}
  th{font-size:11px;text-transform:uppercase;letter-spacing:.04em;color:var(--grey);font-weight:600}
  tr:last-child td{border-bottom:none}
  td.num,th.num{text-align:right;font-variant-numeric:tabular-nums}
  .name{font-weight:600}
  .muted{color:var(--grey)}
</style>
</head>
<body>
@include('partials.appbar', ['active' => 'training'])
<div class="wrap">
  <h1>Treningstider</h1>
  <p class="sub">Lagene som skal fordeles treningstid, importert fra klubbens lagdata.</p>

  <nav class="subnav">
    <a href="/treningstider">Kontroll</a>
    <a href="/treningstider/lag" class="active">Lag</a>
  </nav>

  @php $grouped = $teams->groupBy(fn ($t) => $t->category?->name ?? 'Uten idrett'); @endphp

  @if($teams->isEmpty())
    <div class="card" style="padding:24px;color:var(--ink-soft)">
      Ingen lag importert ennå. Kjør <code>php artisan db:seed --class=TrainingFlikSeeder</code> på serveren.
    </div>
  @else
    <p class="sub">{{ $teams->count() }} lag i {{ $grouped->count() }} idretter.</p>
    @foreach($grouped as $idrett => $lag)
      <h2><span class="idchip" style="background:{{ optional($lag->first()->category)->color ?? '#8795a3' }}"></span>{{ $idrett }} <span class="muted" style="font-weight:400">({{ $lag->count() }})</span></h2>
      <div class="card">
        <table>
          <thead>
            <tr>
              <th>Lag</th><th>Årskull</th><th>Trinn</th>
              <th class="num">Spillere</th><th class="num">Trenere</th><th class="num">Økter/uke</th>
              <th>Areal inne</th><th>Areal ute</th>
            </tr>
          </thead>
          <tbody>
            @foreach($lag->sortBy('name') as $t)
              <tr>
                <td class="name">{{ $t->name }}</td>
                <td class="muted">{{ $t->birth_year ?: '–' }}</td>
                <td class="muted">{{ $t->grade ?: '–' }}</td>
                <td class="num">{{ $t->players ?? '–' }}</td>
                <td class="num">{{ $t->coaches ?? '–' }}</td>
                <td class="num">{{ $t->sessions_per_week ?? '–' }}</td>
                <td class="muted">{{ $t->area_indoor ?: '–' }}</td>
                <td class="muted">{{ $t->area_outdoor ?: '–' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endforeach
  @endif
</div>
</body>
</html>
