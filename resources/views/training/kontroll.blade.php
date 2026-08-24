<!DOCTYPE html>
<html lang="no" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<title>Treningstider · Vivu Planner</title>
@verbatim
<style>
:root{
  color-scheme: light;
  --plane:#f3f5fb; --surface:#ffffff;
  --ink:#1a1f33; --ink2:#5b6b86; --muted:#8795a3;
  --grid:#e6eaf2; --axis:#cddcf0; --ring:rgba(20,40,80,0.10);
  --flik:#2f6fd6; --spind:#fb471f; --bobcats:#1a9aa0;
  --good:#2e9e5b; --warning:#e8a200; --serious:#ec835a; --critical:#d64545;
  --seq1:#dbe7f5; --seq4:#2f6fd6; --seq6:#1c3155;
}
html[data-theme="dark"]{
  color-scheme: dark;
  --plane:#0d0d0d; --surface:#1a1a19;
  --ink:#ffffff; --ink2:#c3c2b7; --muted:#898781;
  --grid:#2c2c2a; --axis:#383835; --ring:rgba(255,255,255,0.10);
  --flik:#3987e5; --spind:#d95926; --bobcats:#199e70;
  --seq1:#104281; --seq4:#3987e5; --seq6:#9ec5f4;
}
*{box-sizing:border-box}
body{margin:0;background:var(--plane);color:var(--ink);
  font-family:'Ubuntu',system-ui,-apple-system,sans-serif;font-size:14px;line-height:1.55;
  -webkit-font-smoothing:antialiased}
.wrap{max-width:1180px;margin:0 auto;padding:28px 20px 72px}
header.top{display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start;justify-content:space-between;margin-bottom:6px}
h1{font-size:22px;font-weight:650;margin:0 0 4px;letter-spacing:-0.01em}
.sub{color:var(--ink2);font-size:13px;max-width:62ch;margin:0}
.demo-tag{display:inline-block;font-size:11px;font-weight:650;letter-spacing:.04em;text-transform:uppercase;
  color:var(--ink2);border:1px solid var(--ring);border-radius:999px;padding:3px 9px;margin-bottom:9px}
button.tgl{background:var(--surface);color:var(--ink2);border:1px solid var(--ring);border-radius:8px;
  padding:7px 12px;font:inherit;font-size:12.5px;cursor:pointer}
button.tgl:hover{color:var(--ink)}
section{background:var(--surface);border:1px solid var(--ring);border-radius:12px;padding:20px;margin-top:18px}
h2{font-size:15px;font-weight:650;margin:0 0 3px}
.note{color:var(--ink2);font-size:12.5px;margin:0 0 16px;max-width:78ch}
.tiles{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-top:16px}
.tile{background:var(--surface);border:1px solid var(--ring);border-radius:12px;padding:14px 16px}
.tile .lbl{font-size:11.5px;color:var(--muted);text-transform:uppercase;letter-spacing:.045em;font-weight:600}
.tile .val{font-size:27px;font-weight:640;letter-spacing:-0.02em;margin-top:5px;line-height:1.1}
.tile .foot{font-size:12px;color:var(--ink2);margin-top:2px}
.rules{display:grid;grid-template-columns:repeat(auto-fit,minmax(268px,1fr));gap:10px}
.rule{display:flex;gap:10px;align-items:flex-start;border:1px solid var(--ring);border-radius:10px;padding:11px 13px;cursor:pointer}
.rule:hover{border-color:var(--axis)}
.rule input{margin:2px 0 0;accent-color:var(--flik);width:15px;height:15px;flex:none}
.rule .rt{font-weight:600;font-size:13px;display:block}
.rule .rd{color:var(--ink2);font-size:12px;margin-top:1px;display:block}
.rule .cnt{margin-left:auto;font-variant-numeric:tabular-nums;font-weight:650;font-size:13px;flex:none;padding-left:8px}
.chip{display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:640;
  border-radius:999px;padding:2px 9px;border:1px solid var(--ring);white-space:nowrap}
.chip svg{width:12px;height:12px;flex:none}
.c-critical{color:var(--critical)} .c-serious{color:var(--serious)}
.c-warning{color:var(--warning)} .c-good{color:var(--good)}
ul.findings{list-style:none;margin:0;padding:0}
ul.findings li{border-top:1px solid var(--grid);padding:11px 2px;display:flex;gap:12px;align-items:flex-start}
ul.findings li:first-child{border-top:none}
ul.findings .body{flex:1;min-width:0}
ul.findings .ttl{font-weight:600;display:block}
ul.findings .det{color:var(--ink2);font-size:12.5px;margin-top:2px;display:block}
ul.findings ol{margin:8px 0 0;padding-left:20px;color:var(--ink2);font-size:12.5px}
ul.findings ol li{padding:2px 0;border:none;display:list-item}
ul.findings ol li b{color:var(--ink);font-weight:600}
table{border-collapse:separate;border-spacing:0;width:100%;font-size:13px}
th,td{text-align:left;padding:7px 9px;border-bottom:1px solid var(--grid)}
th{font-size:11.5px;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);font-weight:600;
  border-bottom:1px solid var(--axis);position:sticky;top:0;background:var(--surface)}
td.num,th.num{text-align:right;font-variant-numeric:tabular-nums}
.scroll{overflow:auto;max-height:520px;border:1px solid var(--grid);border-radius:10px}
.vtabs{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}
.vtab{background:transparent;border:1px solid var(--ring);border-radius:8px;padding:6px 11px;font:inherit;
  font-size:12.5px;color:var(--ink2);cursor:pointer}
.vtab[aria-selected="true"]{border-color:var(--flik);color:var(--ink);font-weight:640;
  background:color-mix(in srgb,var(--flik) 8%,var(--surface))}
.gridwrap{overflow-x:auto}
table.sched{font-size:12px;min-width:640px}
table.sched th.tcol{width:58px}
table.sched td{border-bottom:2px solid var(--surface);border-right:2px solid var(--surface);padding:0;vertical-align:top}
table.sched td .cell{border-radius:4px;padding:4px 7px;min-height:26px;font-size:11.5px;line-height:1.35;
  border-left:3px solid transparent}
.cell.o-FLIK{background:color-mix(in srgb,var(--flik) 12%,var(--surface));border-left-color:var(--flik)}
.cell.o-Spind{background:color-mix(in srgb,var(--spind) 14%,var(--surface));border-left-color:var(--spind)}
.cell.o-Bobcats{background:color-mix(in srgb,var(--bobcats) 14%,var(--surface));border-left-color:var(--bobcats)}
.cell.empty{background:transparent;color:var(--muted);border-left-color:var(--grid)}
.cell.flag{outline:2px solid var(--critical);outline-offset:-2px}
.cell .lab{font-weight:600;color:var(--ink)}
.cell .org{font-size:10.5px;color:var(--ink2)}
.tlabel{font-variant-numeric:tabular-nums;color:var(--ink2);font-size:11.5px;padding:5px 8px 0 0 !important;white-space:nowrap}
.legend{display:flex;flex-wrap:wrap;gap:14px;margin:0 0 12px;font-size:12px;color:var(--ink2)}
.legend span{display:inline-flex;align-items:center;gap:6px}
.sw{width:11px;height:11px;border-radius:3px;display:inline-block}
.bars{display:grid;grid-template-columns:auto 1fr auto;gap:3px 10px;align-items:center;font-size:12px}
.bars .bn{color:var(--ink2);text-align:right;white-space:nowrap;font-variant-numeric:tabular-nums}
.bars .bt{height:11px;border-radius:0 4px 4px 0;background:var(--seq4)}
.bars .bv{font-variant-numeric:tabular-nums;color:var(--ink2);white-space:nowrap}
.bars .btrack{background:color-mix(in srgb,var(--grid) 60%,transparent);border-radius:0 4px 4px 0}
details{margin-top:12px}
summary{cursor:pointer;font-size:12.5px;color:var(--ink2)}
summary:hover{color:var(--ink)}
.small{font-size:12px;color:var(--ink2)}
.warnbox{border:1px solid var(--ring);border-left:3px solid var(--warning);border-radius:8px;
  padding:11px 14px;font-size:12.5px;color:var(--ink2);margin-top:14px}
footer{margin-top:26px;font-size:12px;color:var(--muted);max-width:80ch}
@media print{.tgl,.vtabs{display:none}}
</style>
</head>
@endverbatim
<body>
@include('partials.topbar', ['active' => 'training'])
@include('partials.topbar-js')
@verbatim
<div class="wrap">
<style>
.sgrid{display:grid;grid-template-columns:60px repeat(5,minmax(120px,1fr));gap:2px;min-width:700px}
.sgrid .hd{font-size:11.5px;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);
  font-weight:600;padding:0 0 6px 2px;border-bottom:1px solid var(--axis);margin-bottom:4px}
.sgrid .tl{font-size:11.5px;color:var(--muted);font-variant-numeric:tabular-nums;padding-top:3px}
.sgrid .blk{border-radius:5px;padding:5px 8px;border-left:3px solid transparent;overflow:hidden;
  display:flex;flex-direction:column;justify-content:center;cursor:default}
.sgrid .blk.o-FLIK{background:color-mix(in srgb,var(--flik) 13%,var(--surface));border-left-color:var(--flik)}
.sgrid .blk.o-Spind{background:color-mix(in srgb,var(--spind) 15%,var(--surface));border-left-color:var(--spind)}
.sgrid .blk.o-Bobcats{background:color-mix(in srgb,var(--bobcats) 15%,var(--surface));border-left-color:var(--bobcats)}
.sgrid .blk.locked{background-image:repeating-linear-gradient(135deg,transparent 0 5px,var(--ring) 5px 6px)}
.sgrid .blk.flag{box-shadow:inset 0 0 0 2px var(--critical)}
.sgrid .blk .lab{font-weight:640;font-size:12px;color:var(--ink)}
.sgrid .blk .meta{font-size:10.5px;color:var(--ink2);margin-top:1px}
.sgrid .free{border-radius:5px;border:1px dashed var(--grid)}
</style>

<header class="top">
  <div>
    <div class="demo-tag">Kontroll · IR-tildeling lest inn og sjekket mot reglene</div>
    <h1>Treningstider 2026/2027</h1>
    <p class="sub">Idrettsrådets tildeling, lest inn og kontrollert maskinelt. Skru reglene av og på
    i panelet under og se hvor mange brudd som slår ut. Kilde: <em>Treningstider utendørs 2027A.xlsx</em> (utarbeidet av sportslig ansvarlig og daglig leder) · 234 halvtimesblokker · 9 anlegg · kun fotball.</p>
  </div>
  <button class="tgl" id="theme">Mørk visning</button>
</header>

<nav style="display:flex;gap:6px;margin:4px 0 2px">
  <a href="/treningstider" style="padding:7px 14px;border-radius:9px;border:1px solid var(--flik);color:var(--flik);background:color-mix(in srgb,var(--flik) 8%,var(--surface));font-weight:600;text-decoration:none">Kontroll</a>
  <a href="/treningstider/lag" style="padding:7px 14px;border-radius:9px;border:1px solid var(--ring);color:var(--ink2);text-decoration:none">Lag</a>
  <a href="/treningstider/anlegg" style="padding:7px 14px;border-radius:9px;border:1px solid var(--ring);color:var(--ink2);text-decoration:none">Anlegg</a>
  <a href="/treningstider/rutenett" style="padding:7px 14px;border-radius:9px;border:1px solid var(--ring);color:var(--ink2);text-decoration:none">Rutenett</a>
</nav>

<div class="tiles">
  <div class="tile"><div class="lbl">FLIK disponerer</div><div class="val" id="t-flik">–</div><div class="foot">timer per uke</div></div>
  <div class="tile"><div class="lbl">Låst hos andre</div><div class="val" id="t-lock">–</div><div class="foot">Spind og Bobcats</div></div>
  <div class="tile"><div class="lbl">Lag og grupper</div><div class="val" id="t-teams">–</div><div class="foot">som skal ha tid</div></div>
  <div class="tile"><div class="lbl">Regelbrudd nå</div><div class="val" id="t-viol">–</div><div class="foot" id="t-viol-f">med valgte regler</div></div>
</div>

<section>
  <h2>Regler</h2>
  <p class="note">Dette er reglene maskinen sjekker. I den ferdige løsningen kommer de fra sportslig
  leder – her er de satt opp som eksempel for å vise mekanikken.</p>
  <div class="rules" id="rules"></div>
  <div class="warnbox" id="dupbox"></div>
</section>

<section>
  <h2>Funn</h2>
  <p class="note">Sortert etter alvorlighet. Hvert funn peker på konkret dag, tid og anlegg.</p>
  <ul class="findings" id="findings"></ul>
</section>

<section>
  <h2>Timeplan per anlegg</h2>
  <p class="note">Skravert felt er låst hos annen klubb og kan ikke røres. Rød ramme = blokken er
  involvert i et regelbrudd med gjeldende innstillinger.</p>
  <div class="legend">
    <span><i class="sw" style="background:var(--flik)"></i>FLIK</span>
    <span><i class="sw" style="background:var(--spind)"></i>Spind (låst)</span>
    <span><i class="sw" style="background:var(--bobcats)"></i>Bobcats (låst)</span>
    <span><i class="sw" style="border:1px dashed var(--axis);background:transparent"></i>Ikke tildelt</span>
  </div>
  <div class="vtabs" id="vtabs"></div>
  <div class="gridwrap"><div class="sgrid" id="sgrid"></div></div>
  <p class="small" id="vnote" style="margin:12px 0 0"></p>
</section>

<section>
  <h2>Lagoversikt</h2>
  <p class="note">Én rad per lag. Tall er antall økter den dagen. «Inne» viser om laget har tid i
  Alcoa fotballhall. Delt bane teller som tid for begge lag.</p>
  <div class="scroll"><table id="teamtbl"><thead></thead><tbody></tbody></table></div>
</section>

<section>
  <h2>Tildelt tid per lag</h2>
  <p class="note">Timer per uke slik tildelingen står nå. Ujevnheten er ikke nødvendigvis feil –
  eldre lag skal trene mer – men den bør være et bevisst valg.</p>
  <div class="bars" id="bars"></div>
  <details>
    <summary>Vis som tabell</summary>
    <div class="scroll" style="margin-top:10px"><table id="hourtbl"><thead>
      <tr><th>Lag</th><th class="num">Timer/uke</th><th class="num">Økter/uke</th></tr></thead><tbody></tbody></table></div>
  </details>
</section>

<footer>
  Lagkoder er tolket automatisk: «J/G 8» som J8 og G8, «G 10/11» som G10 og G11. Dette bør
  kvalitetssikres av noen som kjenner lagstrukturen. Håndballens halltider ligger ikke i kildefila,
  så kollisjoner mellom håndball og fotball for samme årskull kan ikke sjekkes her ennå.
</footer>
</div>
<script>const DATA={"slots": [{"venue": "3er bane A", "day": "Onsdag", "time": "17:30", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane A", "day": "Onsdag", "time": "18:00", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane A", "day": "Onsdag", "time": "18:30", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane A", "day": "Tirsdag", "time": "16:00", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane A", "day": "Tirsdag", "time": "16:30", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane A", "day": "Tirsdag", "time": "17:00", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane A", "day": "Torsdag", "time": "16:00", "org": "FLIK", "text": "J/G-7"}, {"venue": "3er bane A", "day": "Torsdag", "time": "16:30", "org": "FLIK", "text": "J/G-7"}, {"venue": "3er bane A", "day": "Torsdag", "time": "17:00", "org": "FLIK", "text": "J/G-7"}, {"venue": "3er bane B", "day": "Onsdag", "time": "17:30", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane B", "day": "Onsdag", "time": "18:00", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane B", "day": "Onsdag", "time": "18:30", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane B", "day": "Tirsdag", "time": "16:00", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane B", "day": "Tirsdag", "time": "16:30", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane B", "day": "Tirsdag", "time": "17:00", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane B", "day": "Torsdag", "time": "16:00", "org": "FLIK", "text": "J/G-7"}, {"venue": "3er bane B", "day": "Torsdag", "time": "16:30", "org": "FLIK", "text": "J/G-7"}, {"venue": "3er bane B", "day": "Torsdag", "time": "17:00", "org": "FLIK", "text": "J/G-7"}, {"venue": "3er bane C", "day": "Onsdag", "time": "17:30", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane C", "day": "Onsdag", "time": "18:00", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane C", "day": "Onsdag", "time": "18:30", "org": "FLIK", "text": "J/G-8"}, {"venue": "3er bane C", "day": "Tirsdag", "time": "16:00", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane C", "day": "Tirsdag", "time": "16:30", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane C", "day": "Tirsdag", "time": "17:00", "org": "FLIK", "text": "J/G-6"}, {"venue": "3er bane C", "day": "Torsdag", "time": "16:00", "org": "FLIK", "text": "J/G-7"}, {"venue": "3er bane C", "day": "Torsdag", "time": "16:30", "org": "FLIK", "text": "J/G-7"}, {"venue": "3er bane C", "day": "Torsdag", "time": "17:00", "org": "FLIK", "text": "J/G-7"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "16:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "16:30", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "17:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "17:30", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "18:00", "org": "Bobcats", "text": "BobCats"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "18:30", "org": "Bobcats", "text": "BobCats"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "19:00", "org": "Bobcats", "text": "BobCats"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "19:30", "org": "Bobcats", "text": "BobCats"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "20:00", "org": "Bobcats", "text": "BobCats"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "20:30", "org": "Bobcats", "text": "BobCats"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "21:00", "org": "Bobcats", "text": "BobCats"}, {"venue": "Alcoa Gress bane", "day": "Mandag", "time": "21:30", "org": "Bobcats", "text": "BobCats"}, {"venue": "Alcoa Gress bane", "day": "Tirsdag", "time": "16:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Tirsdag", "time": "16:30", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Tirsdag", "time": "17:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Tirsdag", "time": "17:30", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Tirsdag", "time": "18:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Tirsdag", "time": "18:30", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Tirsdag", "time": "19:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Torsdag", "time": "16:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Torsdag", "time": "16:30", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Torsdag", "time": "17:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Torsdag", "time": "17:30", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Torsdag", "time": "18:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Torsdag", "time": "18:30", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Gress bane", "day": "Torsdag", "time": "19:00", "org": "FLIK", "text": "Kamper"}, {"venue": "Alcoa Kunstgressbane", "day": "Fredag", "time": "17:30", "org": "FLIK", "text": "G 19/G14"}, {"venue": "Alcoa Kunstgressbane", "day": "Fredag", "time": "18:00", "org": "FLIK", "text": "G 19/G14"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "16:00", "org": "FLIK", "text": "G-10"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "16:30", "org": "FLIK", "text": "G-10"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "17:00", "org": "FLIK", "text": "G-10"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "17:30", "org": "FLIK", "text": "J-15"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "18:00", "org": "FLIK", "text": "J-15"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "19:00", "org": "FLIK", "text": "G16/G14"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "19:30", "org": "FLIK", "text": "G16/G14"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "20:00", "org": "FLIK", "text": "G16/G14"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "20:30", "org": "FLIK", "text": "B-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "21:00", "org": "FLIK", "text": "B-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "time": "21:30", "org": "FLIK", "text": "B-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "16:00", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "16:30", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "17:00", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "17:30", "org": "FLIK", "text": "G-12/J9"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "18:00", "org": "FLIK", "text": "G-12/J9"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "19:00", "org": "FLIK", "text": "Old boys"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "19:30", "org": "FLIK", "text": "Old boys"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "20:00", "org": "FLIK", "text": "Old boys"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "20:30", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "21:00", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "time": "21:30", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa Kunstgressbane", "day": "Tirsdag", "time": "17:30", "org": "FLIK", "text": "G-9/G12"}, {"venue": "Alcoa Kunstgressbane", "day": "Tirsdag", "time": "18:00", "org": "FLIK", "text": "G-9/G12"}, {"venue": "Alcoa Kunstgressbane", "day": "Tirsdag", "time": "19:00", "org": "FLIK", "text": "A-Dame"}, {"venue": "Alcoa Kunstgressbane", "day": "Tirsdag", "time": "19:30", "org": "FLIK", "text": "A-Dame"}, {"venue": "Alcoa Kunstgressbane", "day": "Tirsdag", "time": "20:00", "org": "FLIK", "text": "A-Dame"}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "time": "16:00", "org": "FLIK", "text": "J-10/G-10"}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "time": "16:30", "org": "FLIK", "text": "J-10/G-10"}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "time": "17:00", "org": "FLIK", "text": "J-10/G-10"}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "time": "17:30", "org": "FLIK", "text": "G13/J-12"}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "time": "18:00", "org": "FLIK", "text": "G13/J-12"}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "time": "19:00", "org": "FLIK", "text": "A-Herre/G-19"}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "time": "19:30", "org": "FLIK", "text": "A-Herre/G-19"}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "time": "20:00", "org": "FLIK", "text": "A-Herre/G-19"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "16:00", "org": "FLIK", "text": "Friidrett 12-18"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "16:30", "org": "FLIK", "text": "Friidrett 12-18"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "17:00", "org": "FLIK", "text": "Friidrett 12-18"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "17:30", "org": "FLIK", "text": "G-12"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "18:00", "org": "FLIK", "text": "G-12"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "18:30", "org": "FLIK", "text": "G-12"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "19:00", "org": "FLIK", "text": "B-Herre"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "19:30", "org": "FLIK", "text": "B-Herre"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "20:00", "org": "FLIK", "text": "B-Herre"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "20:30", "org": "FLIK", "text": "Old Boys"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "21:00", "org": "FLIK", "text": "Old Boys"}, {"venue": "Alcoa fotball hall", "day": "Fredag", "time": "21:30", "org": "FLIK", "text": "Old Boys"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "16:00", "org": "FLIK", "text": "Friidrett 7-12/J-9"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "16:30", "org": "FLIK", "text": "Friidrett 7-12/J-9"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "17:00", "org": "FLIK", "text": "Friidrett 7-12/J-9"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "17:30", "org": "FLIK", "text": "G11/12"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "18:00", "org": "FLIK", "text": "G11/12"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "18:30", "org": "FLIK", "text": "G11/12"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "19:00", "org": "FLIK", "text": "G-13/G-12"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "19:30", "org": "FLIK", "text": "G-13/G-12"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "20:00", "org": "FLIK", "text": "G-13/G-12"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "20:30", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "21:00", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa fotball hall", "day": "Mandag", "time": "21:30", "org": "FLIK", "text": "A-Herre"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "16:00", "org": "FLIK", "text": "G8"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "16:30", "org": "FLIK", "text": "G8"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "17:00", "org": "FLIK", "text": "G8"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "17:30", "org": "FLIK", "text": "J/G-8"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "18:00", "org": "FLIK", "text": "J/G-8"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "18:30", "org": "FLIK", "text": "J/G-8"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "19:00", "org": "FLIK", "text": "J-15"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "19:30", "org": "FLIK", "text": "J-15"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "20:00", "org": "FLIK", "text": "J-15"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "20:30", "org": "FLIK", "text": "G16/G14"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "21:00", "org": "FLIK", "text": "G16/G14"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "21:30", "org": "FLIK", "text": "G16/G14"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "16:00", "org": "FLIK", "text": "G-9"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "16:30", "org": "FLIK", "text": "G-9"}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "time": "17:00", "org": "FLIK", "text": "G-9"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "16:00", "org": "FLIK", "text": "J/G-6"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "16:30", "org": "FLIK", "text": "J/G-6"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "17:00", "org": "FLIK", "text": "J/G-6"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "17:30", "org": "FLIK", "text": "G-10"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "18:00", "org": "FLIK", "text": "G-10"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "18:30", "org": "FLIK", "text": "G-10"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "19:00", "org": "FLIK", "text": "J-10/J-12"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "19:30", "org": "FLIK", "text": "J-10/J-12"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "20:00", "org": "FLIK", "text": "J-10/J-12"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "20:30", "org": "FLIK", "text": "G-19"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "21:00", "org": "FLIK", "text": "G-19"}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "time": "21:30", "org": "FLIK", "text": "G-19"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "16:00", "org": "FLIK", "text": "J/G-7"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "16:30", "org": "FLIK", "text": "J/G-7"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "17:00", "org": "FLIK", "text": "J/G-7"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "17:30", "org": "Bobcats", "text": "Bobcats 4-7kl/8-10kl"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "18:00", "org": "Bobcats", "text": "Bobcats 4-7kl/8-10kl"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "18:30", "org": "Bobcats", "text": "Bobcats 4-7kl/8-10kl"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "19:00", "org": "Bobcats", "text": "Bobcats senior"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "19:30", "org": "Bobcats", "text": "Bobcats senior"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "20:00", "org": "Bobcats", "text": "Bobcats senior"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "20:30", "org": "FLIK", "text": "A-Dame"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "21:00", "org": "FLIK", "text": "A-Dame"}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "time": "21:30", "org": "FLIK", "text": "A-Dame"}, {"venue": "Lista Ungdomsskole", "day": "Fredag", "time": "17:30", "org": "FLIK", "text": "G 19/G14"}, {"venue": "Lista Ungdomsskole", "day": "Fredag", "time": "18:00", "org": "FLIK", "text": "G 19/G14"}, {"venue": "Lista Ungdomsskole", "day": "Fredag", "time": "18:30", "org": "FLIK", "text": "G 19/G14"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "16:00", "org": "FLIK", "text": "J/G-8/G-10"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "16:30", "org": "FLIK", "text": "J/G-8/G-10"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "17:00", "org": "FLIK", "text": "J/G-8/G-10"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "17:30", "org": "FLIK", "text": "J-15/J-9"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "18:00", "org": "FLIK", "text": "J-15/J-9"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "18:30", "org": "FLIK", "text": "J-15/J-9"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "19:00", "org": "FLIK", "text": "G16/G14"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "19:30", "org": "FLIK", "text": "G16/G14"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "20:00", "org": "FLIK", "text": "G16/G14"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "20:30", "org": "FLIK", "text": "B-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "21:00", "org": "FLIK", "text": "B-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "time": "21:30", "org": "FLIK", "text": "B-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "16:00", "org": "FLIK", "text": "A-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "16:30", "org": "FLIK", "text": "A-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "17:00", "org": "FLIK", "text": "A-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "19:00", "org": "FLIK", "text": "Old boys"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "19:30", "org": "FLIK", "text": "Old boys"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "20:00", "org": "FLIK", "text": "Old boys"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "20:30", "org": "FLIK", "text": "A-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "21:00", "org": "FLIK", "text": "A-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "time": "21:30", "org": "FLIK", "text": "A-Herre"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "16:00", "org": "FLIK", "text": "G-10"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "16:30", "org": "FLIK", "text": "G-10"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "17:00", "org": "FLIK", "text": "G-10"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "17:30", "org": "FLIK", "text": "G-9"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "18:00", "org": "FLIK", "text": "G-9"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "18:30", "org": "FLIK", "text": "G-9"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "19:00", "org": "FLIK", "text": "A-Dame"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "19:30", "org": "FLIK", "text": "A-Dame"}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "time": "20:00", "org": "FLIK", "text": "A-Dame"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "16:00", "org": "FLIK", "text": "J-12/J-10"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "16:30", "org": "FLIK", "text": "J-12/J-10"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "17:00", "org": "FLIK", "text": "J-12/J-10"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "17:30", "org": "FLIK", "text": "G12/G13"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "18:00", "org": "FLIK", "text": "G12/G13"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "18:30", "org": "FLIK", "text": "G12/G13"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "19:00", "org": "FLIK", "text": "A-Herre/G-19"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "19:30", "org": "FLIK", "text": "A-Herre/G-19"}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "time": "20:00", "org": "FLIK", "text": "A-Herre/G-19"}, {"venue": "Spind kunstgress", "day": "Mandag", "time": "17:30", "org": "Spind", "text": "G11/12"}, {"venue": "Spind kunstgress", "day": "Mandag", "time": "18:00", "org": "Spind", "text": "G11/12"}, {"venue": "Spind kunstgress", "day": "Mandag", "time": "18:30", "org": "Spind", "text": "G11/12"}, {"venue": "Spind kunstgress", "day": "Onsdag", "time": "16:00", "org": "Spind", "text": "G8"}, {"venue": "Spind kunstgress", "day": "Onsdag", "time": "16:30", "org": "Spind", "text": "G8"}, {"venue": "Spind kunstgress", "day": "Onsdag", "time": "17:00", "org": "Spind", "text": "G8"}, {"venue": "Spind kunstgress", "day": "Onsdag", "time": "17:30", "org": "Spind", "text": "G11/12"}, {"venue": "Spind kunstgress", "day": "Onsdag", "time": "18:00", "org": "Spind", "text": "G11/12"}, {"venue": "Spind kunstgress", "day": "Onsdag", "time": "18:30", "org": "Spind", "text": "G11/12"}, {"venue": "Spind kunstgress", "day": "Torsdag", "time": "19:00", "org": "Spind", "text": "Spind mosjon"}, {"venue": "Spind kunstgress", "day": "Torsdag", "time": "19:30", "org": "Spind", "text": "Spind Mosjon"}, {"venue": "Spind kunstgress", "day": "Torsdag", "time": "20:00", "org": "Spind", "text": "Spind mosjon"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "16:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "16:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "17:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "17:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "18:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "18:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "19:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "19:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Fredag", "time": "20:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "16:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "16:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "17:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "17:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "18:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "18:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "19:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "19:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Mandag", "time": "20:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "16:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "16:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "17:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "17:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "18:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "18:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "19:00", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "19:30", "org": "FLIK", "text": "Friidrett"}, {"venue": "Vanse stadion", "day": "Onsdag", "time": "20:00", "org": "FLIK", "text": "Friidrett"}], "blocks": [{"venue": "3er bane A", "day": "Onsdag", "org": "FLIK", "text": "J/G-8", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["J8", "G8"]}, {"venue": "3er bane A", "day": "Tirsdag", "org": "FLIK", "text": "J/G-6", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J6", "G6"]}, {"venue": "3er bane A", "day": "Torsdag", "org": "FLIK", "text": "J/G-7", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J7", "G7"]}, {"venue": "3er bane B", "day": "Onsdag", "org": "FLIK", "text": "J/G-8", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["J8", "G8"]}, {"venue": "3er bane B", "day": "Tirsdag", "org": "FLIK", "text": "J/G-6", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J6", "G6"]}, {"venue": "3er bane B", "day": "Torsdag", "org": "FLIK", "text": "J/G-7", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J7", "G7"]}, {"venue": "3er bane C", "day": "Onsdag", "org": "FLIK", "text": "J/G-8", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["J8", "G8"]}, {"venue": "3er bane C", "day": "Tirsdag", "org": "FLIK", "text": "J/G-6", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J6", "G6"]}, {"venue": "3er bane C", "day": "Torsdag", "org": "FLIK", "text": "J/G-7", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J7", "G7"]}, {"venue": "Alcoa Gress bane", "day": "Mandag", "org": "FLIK", "text": "Kamper", "start": "16:00", "end": "17:30", "slots": 4, "minutes": 120, "endx": "18:00", "teams": ["Kamper"]}, {"venue": "Alcoa Gress bane", "day": "Mandag", "org": "Bobcats", "text": "BobCats", "start": "18:00", "end": "21:30", "slots": 8, "minutes": 240, "endx": "22:00", "teams": ["BobCats"]}, {"venue": "Alcoa Gress bane", "day": "Tirsdag", "org": "FLIK", "text": "Kamper", "start": "16:00", "end": "19:00", "slots": 7, "minutes": 210, "endx": "19:30", "teams": ["Kamper"]}, {"venue": "Alcoa Gress bane", "day": "Torsdag", "org": "FLIK", "text": "Kamper", "start": "16:00", "end": "19:00", "slots": 7, "minutes": 210, "endx": "19:30", "teams": ["Kamper"]}, {"venue": "Alcoa Kunstgressbane", "day": "Fredag", "org": "FLIK", "text": "G 19/G14", "start": "17:30", "end": "18:00", "slots": 2, "minutes": 60, "endx": "18:30", "teams": ["G19", "G14"]}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "org": "FLIK", "text": "G-10", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["G10"]}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "org": "FLIK", "text": "J-15", "start": "17:30", "end": "18:00", "slots": 2, "minutes": 60, "endx": "18:30", "teams": ["J15"]}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "org": "FLIK", "text": "G16/G14", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["G16", "G14"]}, {"venue": "Alcoa Kunstgressbane", "day": "Mandag", "org": "FLIK", "text": "B-Herre", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["B-Herre"]}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "org": "FLIK", "text": "A-Herre", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["A-Herre"]}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "org": "FLIK", "text": "G-12/J9", "start": "17:30", "end": "18:00", "slots": 2, "minutes": 60, "endx": "18:30", "teams": ["G12", "J9"]}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "org": "FLIK", "text": "Old boys", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["Old boys"]}, {"venue": "Alcoa Kunstgressbane", "day": "Onsdag", "org": "FLIK", "text": "A-Herre", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["A-Herre"]}, {"venue": "Alcoa Kunstgressbane", "day": "Tirsdag", "org": "FLIK", "text": "G-9/G12", "start": "17:30", "end": "18:00", "slots": 2, "minutes": 60, "endx": "18:30", "teams": ["G9", "G12"]}, {"venue": "Alcoa Kunstgressbane", "day": "Tirsdag", "org": "FLIK", "text": "A-Dame", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["A-Dame"]}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "org": "FLIK", "text": "J-10/G-10", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J10", "G10"]}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "org": "FLIK", "text": "G13/J-12", "start": "17:30", "end": "18:00", "slots": 2, "minutes": 60, "endx": "18:30", "teams": ["G13", "J12"]}, {"venue": "Alcoa Kunstgressbane", "day": "Torsdag", "org": "FLIK", "text": "A-Herre/G-19", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["A-Herre", "G19"]}, {"venue": "Alcoa fotball hall", "day": "Fredag", "org": "FLIK", "text": "Friidrett 12-18", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["Friidrett 12-18"]}, {"venue": "Alcoa fotball hall", "day": "Fredag", "org": "FLIK", "text": "G-12", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["G12"]}, {"venue": "Alcoa fotball hall", "day": "Fredag", "org": "FLIK", "text": "B-Herre", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["B-Herre"]}, {"venue": "Alcoa fotball hall", "day": "Fredag", "org": "FLIK", "text": "Old Boys", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["Old Boys"]}, {"venue": "Alcoa fotball hall", "day": "Mandag", "org": "FLIK", "text": "Friidrett 7-12/J-9", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["Friidrett 7-12", "J9"]}, {"venue": "Alcoa fotball hall", "day": "Mandag", "org": "FLIK", "text": "G11/12", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["G11", "12"]}, {"venue": "Alcoa fotball hall", "day": "Mandag", "org": "FLIK", "text": "G-13/G-12", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["G13", "G12"]}, {"venue": "Alcoa fotball hall", "day": "Mandag", "org": "FLIK", "text": "A-Herre", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["A-Herre"]}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "org": "FLIK", "text": "G8", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["G8"]}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "org": "FLIK", "text": "J/G-8", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["J8", "G8"]}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "org": "FLIK", "text": "J-15", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["J15"]}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "org": "FLIK", "text": "G16/G14", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["G16", "G14"]}, {"venue": "Alcoa fotball hall", "day": "Onsdag", "org": "FLIK", "text": "G-9", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["G9"]}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "org": "FLIK", "text": "J/G-6", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J6", "G6"]}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "org": "FLIK", "text": "G-10", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["G10"]}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "org": "FLIK", "text": "J-10/J-12", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["J10", "J12"]}, {"venue": "Alcoa fotball hall", "day": "Tirsdag", "org": "FLIK", "text": "G-19", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["G19"]}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "org": "FLIK", "text": "J/G-7", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J7", "G7"]}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "org": "Bobcats", "text": "Bobcats 4-7kl/8-10kl", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["Bobcats 4-7kl", "8-10kl"]}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "org": "Bobcats", "text": "Bobcats senior", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["Bobcats senior"]}, {"venue": "Alcoa fotball hall", "day": "Torsdag", "org": "FLIK", "text": "A-Dame", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["A-Dame"]}, {"venue": "Lista Ungdomsskole", "day": "Fredag", "org": "FLIK", "text": "G 19/G14", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["G19", "G14"]}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "org": "FLIK", "text": "J/G-8/G-10", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J", "G8", "G10"]}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "org": "FLIK", "text": "J-15/J-9", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["J15", "J9"]}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "org": "FLIK", "text": "G16/G14", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["G16", "G14"]}, {"venue": "Lista Ungdomsskole", "day": "Mandag", "org": "FLIK", "text": "B-Herre", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["B-Herre"]}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "org": "FLIK", "text": "A-Herre", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["A-Herre"]}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "org": "FLIK", "text": "Old boys", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["Old boys"]}, {"venue": "Lista Ungdomsskole", "day": "Onsdag", "org": "FLIK", "text": "A-Herre", "start": "20:30", "end": "21:30", "slots": 3, "minutes": 90, "endx": "22:00", "teams": ["A-Herre"]}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "org": "FLIK", "text": "G-10", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["G10"]}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "org": "FLIK", "text": "G-9", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["G9"]}, {"venue": "Lista Ungdomsskole", "day": "Tirsdag", "org": "FLIK", "text": "A-Dame", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["A-Dame"]}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "org": "FLIK", "text": "J-12/J-10", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["J12", "J10"]}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "org": "FLIK", "text": "G12/G13", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["G12", "G13"]}, {"venue": "Lista Ungdomsskole", "day": "Torsdag", "org": "FLIK", "text": "A-Herre/G-19", "start": "19:00", "end": "20:00", "slots": 3, "minutes": 90, "endx": "20:30", "teams": ["A-Herre", "G19"]}, {"venue": "Spind kunstgress", "day": "Mandag", "org": "Spind", "text": "G11/12", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["G11", "12"]}, {"venue": "Spind kunstgress", "day": "Onsdag", "org": "Spind", "text": "G8", "start": "16:00", "end": "17:00", "slots": 3, "minutes": 90, "endx": "17:30", "teams": ["G8"]}, {"venue": "Spind kunstgress", "day": "Onsdag", "org": "Spind", "text": "G11/12", "start": "17:30", "end": "18:30", "slots": 3, "minutes": 90, "endx": "19:00", "teams": ["G11", "12"]}, {"venue": "Spind kunstgress", "day": "Torsdag", "org": "Spind", "text": "Spind mosjon", "start": "19:00", "end": "19:00", "slots": 1, "minutes": 30, "endx": "19:30", "teams": ["Spind mosjon"]}, {"venue": "Spind kunstgress", "day": "Torsdag", "org": "Spind", "text": "Spind Mosjon", "start": "19:30", "end": "19:30", "slots": 1, "minutes": 30, "endx": "20:00", "teams": ["Spind Mosjon"]}, {"venue": "Spind kunstgress", "day": "Torsdag", "org": "Spind", "text": "Spind mosjon", "start": "20:00", "end": "20:00", "slots": 1, "minutes": 30, "endx": "20:30", "teams": ["Spind mosjon"]}, {"venue": "Vanse stadion", "day": "Fredag", "org": "FLIK", "text": "Friidrett", "start": "16:00", "end": "20:00", "slots": 9, "minutes": 270, "endx": "20:30", "teams": ["Friidrett"]}, {"venue": "Vanse stadion", "day": "Mandag", "org": "FLIK", "text": "Friidrett", "start": "16:00", "end": "20:00", "slots": 9, "minutes": 270, "endx": "20:30", "teams": ["Friidrett"]}, {"venue": "Vanse stadion", "day": "Onsdag", "org": "FLIK", "text": "Friidrett", "start": "16:00", "end": "20:00", "slots": 9, "minutes": 270, "endx": "20:30", "teams": ["Friidrett"]}], "venues": ["Alcoa fotball hall", "Alcoa Kunstgressbane", "Lista Ungdomsskole", "Alcoa Gress bane", "Vanse stadion", "Spind kunstgress", "3er bane A", "3er bane B", "3er bane C"], "days": ["Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag"], "times": ["16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00", "19:30", "20:00", "20:30", "21:00", "21:30"]};</script>
<script>
const DAYS = DATA.days, TIMES = DATA.times, VENUES = DATA.venues;
const HALL = 'Alcoa fotball hall';
const KG = 'Alcoa Kunstgressbane', LISTA = 'Lista Ungdomsskole';
const is3er = v => /^3er bane/.test(v); // A/B/C er én delt ressurs – samme lag samtidig er OK
const NONTEAM = new Set(['Kamper', 'Bobcats', 'Spind mosjon', 'FLIK (uspes.)']);
const isTeam = t => !NONTEAM.has(t) && !t.startsWith('Friidrett');
const ti = t => TIMES.indexOf(t);
const mins = t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
const short = v => v.replace('Alcoa ', '').replace(' Ungdomsskole', ' u.skole').replace('Kunstgressbane', 'kunstgress').replace('fotball hall', 'fotballhallen').replace('Gress bane', 'gressbane');
const age = t => { const m = /^[JG](\d+)$/.exec(t); return m ? +m[1] : null; };

const RULES = [
  { id: 'dup', ttl: 'Samme lag på to anlegg samtidig', desc: 'Laget kan bare være ett sted.', sev: 'critical', on: true },
  { id: 'inne', ttl: 'Fotballag uten innendørsøkt', desc: 'Alle lag skal ha én trening i fotballhallen.', sev: 'serious', on: true },
  { id: 'sent', ttl: 'Barn (t.o.m. 12 år) som slutter etter 20:00', desc: 'Sluttid for de yngste årskullene.', sev: 'serious', on: true },
  { id: 'flere', ttl: 'Samme lag med to økter samme dag', desc: 'Ikke nødvendigvis feil – men bør være bevisst.', sev: 'warning', on: true },
  { id: 'ledig', ttl: 'Anlegg som står tomt en hel dag', desc: 'Ubrukt kapasitet vi kan søke om.', sev: 'warning', on: true },
];
let listaOff = false;

function activeBlocks() {
  return DATA.blocks.filter(b => !(listaOff && b.venue === LISTA));
}

function analyse() {
  const B = activeBlocks(), F = B.filter(b => b.org === 'FLIK');
  const out = [], flagged = new Set();
  const key = b => `${b.venue}|${b.day}|${b.start}`;
  const on = id => RULES.find(r => r.id === id).on;

  // per team per day
  const td = new Map();
  F.forEach(b => b.teams.forEach(t => {
    if (!isTeam(t)) return;
    const k = t + '|' + b.day;
    if (!td.has(k)) td.set(k, []);
    td.get(k).push(b);
  }));

  if (on('dup')) {
    for (const [k, bs] of td) {
      const [t, d] = k.split('|');
      for (let i = 0; i < bs.length; i++) for (let j = i + 1; j < bs.length; j++) {
        const a = bs[i], c = bs[j];
        if (a.venue !== c.venue && !(is3er(a.venue) && is3er(c.venue)) && mins(a.start) < mins(c.endx) && mins(c.start) < mins(a.endx)) {
          out.push({ sev: 'critical', rule: 'dup', ttl: `${t} står på to anlegg samtidig`,
            det: `${d} ${a.start}–${a.endx} · ${short(a.venue)} og ${short(c.venue)}` });
          flagged.add(key(a)); flagged.add(key(c));
        }
      }
    }
  }
  if (on('inne')) {
    const hall = new Set(), all = new Set();
    F.forEach(b => b.teams.forEach(t => { if (!isTeam(t)) return; all.add(t); if (b.venue === HALL) hall.add(t); }));
    const missing = [...all].filter(t => !hall.has(t)).sort();
    if (missing.length) out.push({ sev: 'serious', rule: 'inne',
      ttl: `${missing.length} lag har ingen tid i fotballhallen`,
      det: missing.join(', ') + (listaOff ? '' : ' — de øvrige 20 lagene har én økt inne.') });
  }
  if (on('sent')) {
    F.forEach(b => b.teams.forEach(t => {
      const a = age(t);
      if (a !== null && a <= 12 && mins(b.endx) > 20 * 60) {
        out.push({ sev: 'serious', rule: 'sent', ttl: `${t} trener til ${b.endx}`,
          det: `${b.day} ${b.start}–${b.endx} · ${short(b.venue)} — ${a} år` });
        flagged.add(key(b));
      }
    }));
  }
  if (on('flere')) {
    for (const [k, bs] of td) {
      const [t, d] = k.split('|');
      const uniq = [...new Map(bs.map(b => [b.start + b.venue, b])).values()];
      const nonOverlap = uniq.filter((a, i) => !uniq.some((c, j) => j !== i && a.venue !== c.venue && mins(a.start) < mins(c.endx) && mins(c.start) < mins(a.endx) && j < i));
      if (nonOverlap.length > 1) {
        const sep = nonOverlap.some((a, i) => nonOverlap.some((c, j) => j > i && (mins(c.start) >= mins(a.endx) || mins(a.start) >= mins(c.endx))));
        if (sep) out.push({ sev: 'warning', rule: 'flere', ttl: `${t} har to økter ${d.toLowerCase()}`,
          det: nonOverlap.map(b => `${b.start}–${b.endx} ${short(b.venue)}`).join(' · ') });
      }
    }
  }
  if (on('ledig')) {
    VENUES.forEach(v => { if (listaOff && v === LISTA) return;
      DAYS.forEach(d => {
        if (!B.some(b => b.venue === v && b.day === d))
          out.push({ sev: 'warning', rule: 'ledig', ttl: `${short(v)} er ikke tildelt noen ${d.toLowerCase()}`,
            det: '16:00–22:00 står tomt — 6 timer kapasitet' });
      });
    });
  }
  const order = { critical: 0, serious: 1, warning: 2 };
  out.sort((a, b) => order[a.sev] - order[b.sev] || a.ttl.localeCompare(b.ttl, 'no'));
  return { findings: out, flagged };
}

const ICON = {
  critical: '<svg viewBox="0 0 16 16" fill="currentColor"><circle cx="8" cy="8" r="7"/><rect x="7.1" y="4" width="1.8" height="5" fill="#fff"/><rect x="7.1" y="10.4" width="1.8" height="1.8" fill="#fff"/></svg>',
  serious: '<svg viewBox="0 0 16 16" fill="currentColor"><path d="M8 1l7 13H1z"/><rect x="7.1" y="6" width="1.8" height="4.2" fill="#fff"/><rect x="7.1" y="11.2" width="1.8" height="1.6" fill="#fff"/></svg>',
  warning: '<svg viewBox="0 0 16 16" fill="currentColor"><circle cx="8" cy="8" r="7"/><rect x="7.1" y="4" width="1.8" height="5" fill="#1a1a19"/><rect x="7.1" y="10.4" width="1.8" height="1.8" fill="#1a1a19"/></svg>',
  good: '<svg viewBox="0 0 16 16" fill="currentColor"><circle cx="8" cy="8" r="7"/><path d="M4.6 8.2l2.2 2.2 4.4-4.6" stroke="#fff" stroke-width="1.9" fill="none"/></svg>',
};
const SEVTXT = { critical: 'Må rettes', serious: 'Bør rettes', warning: 'Til vurdering', good: 'OK' };
const chip = s => `<span class="chip c-${s}">${ICON[s]}${SEVTXT[s]}</span>`;

let venue = VENUES[0];

function render() {
  const { findings, flagged } = analyse();
  const B = activeBlocks();

  // tiles
  const sum = o => B.filter(b => b.org === o).reduce((a, b) => a + b.minutes, 0) / 60;
  const teams = new Set();
  B.filter(b => b.org === 'FLIK').forEach(b => b.teams.forEach(t => { if (isTeam(t)) teams.add(t); }));
  document.getElementById('t-flik').textContent = sum('FLIK').toFixed(1).replace('.', ',') + ' t';
  document.getElementById('t-lock').textContent = (sum('Spind') + sum('Bobcats')).toFixed(1).replace('.', ',') + ' t';
  document.getElementById('t-teams').textContent = teams.size;
  document.getElementById('t-viol').textContent = findings.length;
  const nc = findings.filter(f => f.sev === 'critical').length;
  document.getElementById('t-viol-f').textContent = nc ? `${nc} må rettes` : 'ingen kritiske';

  // rules
  document.getElementById('rules').innerHTML = RULES.map(r => {
    const n = findings.filter(f => f.rule === r.id).length;
    return `<label class="rule"><input type="checkbox" data-r="${r.id}" ${r.on ? 'checked' : ''}>
      <span><span class="rt">${r.ttl}</span><span class="rd">${r.desc}</span></span>
      <span class="cnt c-${n ? r.sev : 'good'}">${r.on ? n : '–'}</span></label>`;
  }).join('');
  document.querySelectorAll('#rules input').forEach(el => el.onchange = e => {
    RULES.find(r => r.id === e.target.dataset.r).on = e.target.checked; render();
  });

  document.getElementById('dupbox').innerHTML =
    `<label style="display:flex;gap:9px;align-items:flex-start;cursor:pointer">
      <input type="checkbox" id="listaoff" ${listaOff ? 'checked' : ''} style="margin-top:2px;width:15px;height:15px;accent-color:var(--flik)">
      <span><strong>Behandle Lista ungdomsskole som en dublett av Alcoa kunstgress.</strong>
      15 av 19 blokker er identiske – samme dag, samme klokkeslett, samme lag. Skru på for å se
      hvor mye av problembildet som forsvinner hvis det bare er en kopi i regnearket.
      ${listaOff ? '<em>På nå: Lista er tatt ut av analysen.</em>' : ''}</span></label>`;
  document.getElementById('listaoff').onchange = e => { listaOff = e.target.checked; render(); };

  // findings – gruppert per regel, med utvidbar liste
  const GROUP = {
    dup: n => `${n} tilfeller: samme lag er satt opp på to anlegg samtidig`,
    inne: () => 'Lag uten innendørsøkt i fotballhallen',
    sent: n => `${n} økter der barn t.o.m. 12 år slutter etter 20:00`,
    flere: n => `${n} tilfeller: samme lag har to økter samme dag`,
    ledig: n => `${n} anlegg–dager uten tildeling`,
  };
  let fh = '';
  RULES.forEach(r => {
    if (!r.on) return;
    const fs = findings.filter(f => f.rule === r.id);
    if (!fs.length) return;
    const head = GROUP[r.id](fs.length);
    const items = fs.map(f => `<li><b>${f.ttl}</b> — ${f.det}</li>`).join('');
    fh += `<li>${chip(r.sev)}<span class="body"><span class="ttl">${head}</span>` +
      (fs.length === 1 ? `<span class="det">${fs[0].det}</span>` :
        `<details${fs.length <= 6 ? ' open' : ''}><summary>Vis alle ${fs.length}</summary><ol>${items}</ol></details>`) +
      `</span></li>`;
  });
  document.getElementById('findings').innerHTML = fh ||
    `<li>${chip('good')}<span class="body"><span class="ttl">Ingen brudd med valgte regler</span></span></li>`;

  // venue tabs
  document.getElementById('vtabs').innerHTML = VENUES.map(v => {
    const n = B.filter(b => b.venue === v).length;
    return `<button class="vtab" data-v="${v}" aria-selected="${v === venue}">${short(v)} <span style="color:var(--muted)">${n}</span></button>`;
  }).join('');
  document.querySelectorAll('.vtab').forEach(el => el.onclick = () => { venue = el.dataset.v; render(); });

  // grid
  let g = `<div class="hd"></div>` + DAYS.map(d => `<div class="hd">${d}</div>`).join('');
  TIMES.forEach((t, i) => { g += `<div class="tl" style="grid-column:1;grid-row:${i + 2}">${t}</div>`; });
  DAYS.forEach((d, di) => {
    const bs = B.filter(b => b.venue === venue && b.day === d)
      .sort((a, b) => ti(a.start) - ti(b.start));
    const used = new Array(TIMES.length).fill(false);
    // Blokker som overlapper i tid (delt anlegg) vises side om side.
    // Bare den overlappende klyngen deles – blokker som står alene får full bredde.
    const ov = (a, c) => ti(a.start) < ti(c.start) + c.slots && ti(c.start) < ti(a.start) + a.slots;
    const clusters = [];
    bs.forEach(b => {
      const hit = clusters.filter(cl => cl.some(o => ov(o, b)));
      if (!hit.length) { clusters.push([b]); return; }
      const merged = hit.flat().concat([b]);
      hit.forEach(h => clusters.splice(clusters.indexOf(h), 1));
      clusters.push(merged);
    });
    clusters.forEach(cl => {
      const lanes = [];
      cl.sort((a, c) => ti(a.start) - ti(c.start)).forEach(b => {
        let L = 0;
        while (lanes[L] && lanes[L].some(o => ov(o, b))) L++;
        (lanes[L] = lanes[L] || []).push(b);
        b._lane = L;
      });
      cl.forEach(b => b._lanes = Math.max(lanes.length, 1));
    });
    bs.forEach(b => {
      const nl = b._lanes || 1;
      const s = ti(b.start), sp = b.slots;
      for (let k = s; k < s + sp; k++) used[k] = true;
      const locked = b.org !== 'FLIK';
      const fl = flagged.has(`${b.venue}|${b.day}|${b.start}`) ? ' flag' : '';
      const w = nl > 1 ? `width:calc(${(100 / nl).toFixed(3)}% - 1px);margin-left:calc(${(b._lane * 100 / nl).toFixed(3)}% + ${b._lane}px);` : '';
      g += `<div class="blk o-${b.org}${locked ? ' locked' : ''}${fl}" style="grid-column:${di + 2};grid-row:${s + 2} / span ${sp};${w}"
        title="${b.venue} · ${d} ${b.start}–${b.endx} · ${b.org}">
        <span class="lab">${b.text}</span><span class="meta">${b.start}–${b.endx}${locked ? ' · ' + b.org : ''}</span></div>`;
    });
    used.forEach((u, k) => { if (!u) g += `<div class="free" style="grid-column:${di + 2};grid-row:${k + 2}"></div>`; });
  });
  document.getElementById('sgrid').innerHTML = g;
  document.getElementById('vnote').innerHTML = venue === HALL
    ? 'Merknad i kildefila: <em>«Onsdag: Spind og FLIK deler hall fra 16:00–17:30.»</em> Delte blokker vises side om side.'
    : (venue === LISTA ? 'Dette anlegget er nesten identisk med Alcoa kunstgress – se regelpanelet over.'
      : (venue === 'Alcoa Gress bane' ? 'Satt opp med kamper, ikke trening. Avklar om kamptider skal ligge i samme oversikt.' : '&nbsp;'));

  // team table
  const rows = [...teams].sort((a, b) => {
    const aa = age(a), ab = age(b);
    if (aa !== null && ab !== null) return aa - ab || a.localeCompare(b, 'no');
    if (aa !== null) return -1; if (ab !== null) return 1;
    return a.localeCompare(b, 'no');
  }).map(t => {
    const bs = B.filter(b => b.org === 'FLIK' && b.teams.includes(t));
    return { t, per: DAYS.map(d => bs.filter(b => b.day === d).length),
      min: bs.reduce((a, b) => a + b.minutes, 0), n: bs.length,
      hall: bs.some(b => b.venue === HALL) };
  });
  document.querySelector('#teamtbl thead').innerHTML =
    `<tr><th>Lag</th>${DAYS.map(d => `<th class="num">${d.slice(0, 3)}</th>`).join('')}<th class="num">Økter</th><th class="num">Timer</th><th>Inne</th></tr>`;
  document.querySelector('#teamtbl tbody').innerHTML = rows.map(r =>
    `<tr><td style="font-weight:600">${r.t}</td>${r.per.map(c => `<td class="num" style="${c ? '' : 'color:var(--muted)'}">${c || '·'}</td>`).join('')}
     <td class="num">${r.n}</td><td class="num">${(r.min / 60).toFixed(1).replace('.', ',')}</td>
     <td>${r.hall ? `<span class="chip c-good">${ICON.good}Ja</span>` : `<span class="chip c-serious">${ICON.serious}Nei</span>`}</td></tr>`).join('');

  // bars
  const hs = rows.slice().sort((a, b) => b.min - a.min);
  const max = Math.max(...hs.map(r => r.min), 1);
  document.getElementById('bars').innerHTML = hs.map(r =>
    `<span class="bn">${r.t}</span><span class="btrack"><span class="bt" style="width:${(r.min / max * 100).toFixed(1)}%;display:block"></span></span><span class="bv">${(r.min / 60).toFixed(1).replace('.', ',')} t</span>`).join('');
  document.querySelector('#hourtbl tbody').innerHTML = hs.map(r =>
    `<tr><td>${r.t}</td><td class="num">${(r.min / 60).toFixed(1).replace('.', ',')}</td><td class="num">${r.n}</td></tr>`).join('');
}

document.getElementById('theme').onclick = () => {
  const d = document.documentElement.dataset.theme === 'dark';
  document.documentElement.dataset.theme = d ? 'light' : 'dark';
  document.getElementById('theme').textContent = d ? 'Mørk visning' : 'Lys visning';
};
render();

</script>
</body>
</html>
@endverbatim
