<style>
.vk{--vk-ink:#1c2733;--vk-soft:#6b7885;--vk-line:#e7ecf2;--vk-accent:#2f6fd6;color:var(--vk-ink);line-height:1.4}
.vk *{box-sizing:border-box}
.vk .vk-day{margin:0 0 1.1em}
.vk .vk-dh{font-size:.8em;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--vk-accent);margin:0 0 .5em;padding-bottom:.35em;border-bottom:2px solid var(--vk-line)}
.vk .vk-m{display:flex;gap:.8em;align-items:flex-start;padding:.6em .2em;border-bottom:1px solid var(--vk-line)}
.vk .vk-m:last-child{border-bottom:0}
.vk .vk-t{flex:none;width:3.4em;font-weight:700;font-variant-numeric:tabular-nums}
.vk .vk-i{flex:1;min-width:0}
.vk .vk-teams{font-weight:600}
.vk .vk-vs{color:var(--vk-soft);font-weight:400;margin:0 .3em}
.vk .vk-meta{font-size:.82em;color:var(--vk-soft);margin-top:.15em}
.vk .vk-sport{display:inline-block;font-size:.72em;font-weight:700;color:#fff;border-radius:.4em;padding:.05em .5em;margin-right:.4em;vertical-align:.08em}
.vk .vk-badge{display:inline-block;font-size:.72em;font-weight:700;color:#b23535;background:#f6e0e0;border-radius:.4em;padding:.05em .5em;margin-left:.4em}
.vk .vk-empty{color:var(--vk-soft);padding:1.4em .5em;text-align:center}
.vk .vk-foot{margin-top:.9em;font-size:.72em;color:#9aa6b2;text-align:center}
</style>
<div class="vk">
@forelse($days as $day)
  <div class="vk-day">
    <div class="vk-dh">{{ $day['label'] }}</div>
    @foreach($day['matches'] as $m)
      <div class="vk-m">
        <div class="vk-t">{{ $m['time'] ?? '' }}</div>
        <div class="vk-i">
          <div class="vk-teams">@if(empty($sport) && !empty($m['sport']))<span class="vk-sport" style="background:{{ $m['color'] ?? '#5a7184' }}">{{ $m['sport'] }}</span>@endif{{ $m['home_team'] }}<span class="vk-vs">–</span>{{ $m['away_team'] }}@if(!empty($m['note']))<span class="vk-badge">{{ $m['note'] }}</span>@endif</div>
          <div class="vk-meta">@if(!empty($m['tournament'])){{ $m['tournament'] }}@endif@if(!empty($m['location'])) · {{ $m['location'] }}@endif</div>
        </div>
      </div>
    @endforeach
  </div>
@empty
  <div class="vk-empty">Ingen hjemmekamper de neste sju dagene.</div>
@endforelse
  <div class="vk-foot">Oppdateres automatisk · {{ $company->name }}</div>
</div>
