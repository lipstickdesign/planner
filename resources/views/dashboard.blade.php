<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Vivu Planner – {{ $company->name ?? 'Årshjul' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
@verbatim
<style>
:root{--flik-blue:#2f6fd6;--flik-blue-dark:#1c3155;--flik-blue-deep:#1a1e39;--navy:#1c3155;--sky:#6aaae4;--orange:#fb471f;--magenta:#cc2170;--accent:#fb471f;--bg:#f3f5fb;--card:#fff;--ink:#1a1f33;--ink-soft:#5b6b86;--line:#e6eaf2;--green:#2e9e5b;--amber:#e8a200;--red:#d64545;--teal:#1a9aa0;--grey:#8795a3;--radius:18px;--shadow:0 2px 5px rgba(20,40,80,.05),0 12px 36px rgba(20,40,80,.07)}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--ink);font-family:'Ubuntu',system-ui,sans-serif;line-height:1.5}
a{color:var(--flik-blue);text-decoration:none}a:hover{text-decoration:underline}
.wrap{max-width:1240px;margin:0 auto;padding:0 20px}
.topbar{background:linear-gradient(125deg,#26406e,#1c3155 55%,#1a1e39);color:#fff}
.topbar .wrap{display:flex;align-items:center;gap:14px;padding:13px 20px}
.brand{display:flex;align-items:center;gap:12px}
.brandmark{height:38px;width:auto;color:#fff;display:block}
.brand h1{font-size:17px;margin:0;font-weight:500}
.brand .sub{font-size:12px;opacity:.82;font-weight:300}
.spacer{flex:1}
.userchip{display:flex;align-items:center;gap:9px;background:rgba(255,255,255,.13);padding:5px 13px;border-radius:24px;font-size:13px}
.userchip .av{width:28px;height:28px;border-radius:50%;background:var(--accent);color:#3a2c00;display:grid;place-items:center;font-weight:700;font-size:11px;margin-left:-6px}
.logoutbtn{background:rgba(255,255,255,.16);border:none;color:#fff;border-radius:20px;padding:7px 13px;cursor:pointer;font-family:inherit;font-size:13px}
.logoutbtn:hover{background:rgba(255,255,255,.3)}
.tabbar{background:var(--flik-blue-deep)}
.tabs{display:flex;gap:2px;max-width:1240px;margin:0 auto;padding:0 14px;flex-wrap:wrap}
.tab{padding:13px 16px;color:rgba(255,255,255,.72);font-size:14px;font-weight:500;cursor:pointer;border:none;background:none;border-bottom:3px solid transparent}
.tab:hover{color:#fff}.tab.active{color:#fff;border-bottom-color:var(--accent)}
main{padding:24px 0 60px}.view{display:none}.view.active{display:block}
.viewhead{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:12px;flex-wrap:wrap}
.viewhead h2{font-size:18px;margin:0}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
.stat{background:var(--card);border-radius:var(--radius);padding:18px;box-shadow:var(--shadow);border:1px solid var(--line);position:relative;overflow:hidden}
.stat .n{font-size:30px;font-weight:700;line-height:1}.stat .l{font-size:13px;color:var(--ink-soft);margin-top:6px}
.stat .bar{position:absolute;left:0;top:0;bottom:0;width:5px}
.stat.b1{background:#eef3fd}.stat.b2{background:#fdf1ea}.stat.b3{background:#fcebf1}.stat.b4{background:#ecf5fa}
.stat.b1 .bar{background:var(--flik-blue)}.stat.b2 .bar{background:var(--orange)}.stat.b3 .bar{background:var(--magenta)}.stat.b4 .bar{background:var(--sky)}
.grid2{display:grid;grid-template-columns:1.35fr 1fr;gap:18px}
.panel{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);overflow:hidden}
.panel h3{font-size:15px;margin:0;padding:15px 18px;border-bottom:1px solid var(--line);font-weight:700;display:flex;align-items:center;gap:9px}
.panel h3 .tag{font-size:11px;font-weight:500;color:#fff;background:var(--red);padding:2px 9px;border-radius:20px}
.panel h3 .tag.cool{background:var(--flik-blue)}
.panel .body{padding:6px 0}
.row{display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid var(--line);cursor:pointer}
.row:last-child{border-bottom:none}.row:hover{background:#f7faff}
.dot{width:11px;height:11px;border-radius:50%;flex:none}
.row .meta{flex:1;min-width:0}
.row .t{font-size:14px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.row .s{font-size:12px;color:var(--ink-soft);margin-top:1px}
.when{font-size:12px;font-weight:500;text-align:right;white-space:nowrap}
.when .d{font-size:11px;color:var(--ink-soft);font-weight:400}.urgent{color:var(--red)}.soon{color:var(--amber)}
.pill{font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;white-space:nowrap;display:inline-block}
.st-planlagt{background:#e7f0fb;color:#1c5fa8}.st-arbeid{background:#fdf0d6;color:#9a6b00}
.st-klar{background:#dcf3ee;color:#137a6e}.st-publisert{background:#dff3e4;color:#1f7a42}
.st-avlyst{background:#eceff2;color:#69788a}.st-mangler{background:#fde3e3;color:#b23535}
.st-godkjent{background:#dff3e4;color:#1f7a42}.st-tilgodkj{background:#fdf0d6;color:#9a6b00}
.wheelwrap{display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start}
.wheelcard{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);padding:10px}
svg.wheel{width:100%;height:auto;display:block}
.legend{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);padding:16px 18px}
.legend h4{margin:0 0 12px;font-size:14px}
.legend .item{display:flex;align-items:center;gap:9px;font-size:13px;padding:4px 0;cursor:pointer;border-radius:6px}
.legend .item:hover{background:#f5f8fc}.legend .item.off{opacity:.32}
.legend .sw{width:12px;height:12px;border-radius:3px;flex:none}.legend .c{margin-left:auto;font-size:12px;color:var(--ink-soft)}
.wheel-hint{font-size:12px;color:var(--ink-soft);margin-top:10px;text-align:center}
.note{background:#fff8e6;border:1px solid #f3e2af;color:#7a5b00;font-size:12.5px;padding:10px 14px;border-radius:10px;margin-bottom:18px}
.brief{background:#eef3fd;border:1px solid #d7e4f8;color:#26344d;font-size:13px;padding:12px 15px;border-radius:12px;margin:0 0 18px;white-space:pre-wrap;line-height:1.55}
.brief b{color:var(--flik-blue-dark)}
.toolbar{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:center}
.toolbar select,.toolbar input{font-family:inherit;font-size:13px;padding:8px 11px;border:1px solid var(--line);border-radius:9px;background:#fff;color:var(--ink)}
.toolbar input{flex:1;min-width:160px}
.monthgroup{margin-bottom:18px}.monthgroup h3{font-size:13px;text-transform:uppercase;letter-spacing:.7px;color:var(--ink-soft);margin:0 0 8px;font-weight:500}
.elist{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);overflow:hidden}
.erow{display:grid;grid-template-columns:70px 1fr auto auto;gap:13px;align-items:center;padding:13px 18px;border-bottom:1px solid var(--line);cursor:pointer}
.erow:last-child{border-bottom:none}.erow:hover{background:#f7faff}
.erow .date{font-size:12px;color:var(--ink-soft);font-weight:500;text-align:center;line-height:1.2}
.erow .date .day{font-size:19px;color:var(--ink);display:block;font-weight:700}
.erow .ti{display:flex;align-items:center;gap:9px;min-width:0}
.erow .ti .nm{font-weight:500;font-size:14.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sport{font-size:12px;color:#fff;padding:2px 9px;border-radius:20px;font-weight:500;white-space:nowrap}
.muted{font-size:12px;color:var(--ink-soft);white-space:nowrap}
.recur{font-size:11px;color:#1c5fa8}
.overlay{position:fixed;inset:0;background:rgba(10,25,45,.55);backdrop-filter:blur(2px);display:none;align-items:flex-start;justify-content:center;padding:36px 16px;z-index:60;overflow:auto}
.overlay.open{display:flex}
.modal{background:var(--card);border-radius:22px;max-width:840px;width:100%;box-shadow:0 30px 80px rgba(0,0,0,.32);overflow:hidden;animation:pop .2s cubic-bezier(.2,.8,.2,1)}
@keyframes pop{from{transform:translateY(12px) scale(.99);opacity:0}to{transform:none;opacity:1}}
.modal .head{padding:28px 32px;color:#fff;position:relative}
.modal .head .idtag{color:rgba(255,255,255,.85);font-size:11px}
.modal .head h2{margin:6px 0 4px;font-size:22px;font-weight:700}
.modal .head .sub{font-size:13.5px;opacity:.92}
.modal .close{position:absolute;top:16px;right:18px;background:rgba(255,255,255,.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;font-size:18px;cursor:pointer}
.modal .close:hover{background:rgba(255,255,255,.34)}
.mbody{padding:28px 32px 32px}
.fieldgrid{display:grid;grid-template-columns:1fr 1fr;gap:20px 32px;margin-bottom:24px;background:#f9fbfd;border:1px solid var(--line);border-radius:14px;padding:18px 20px}
.field .k{color:var(--ink-soft);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px}
.field .v{font-weight:500;font-size:13.5px}.field .v.empty{color:var(--red);font-weight:400;font-style:italic}
.sectionlabel{font-size:13px;font-weight:700;margin:16px 0 10px;display:flex;align-items:center;gap:8px}
.sectionlabel .count{font-size:11px;font-weight:500;color:var(--ink-soft);background:#eef2f7;padding:1px 8px;border-radius:20px}
.timeline{position:relative;margin-left:6px;padding-left:20px;border-left:2px dashed var(--line)}
.post{position:relative;padding:0 0 15px}.post:last-child{padding-bottom:2px}
.post .pin{position:absolute;left:-27px;top:2px;width:13px;height:13px;border-radius:50%;background:#fff;border:3px solid var(--flik-blue)}
.post .ph{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.post .pd{font-size:12.5px;font-weight:700}.post .pt{font-size:13.5px;font-weight:500}
.post .pmeta{font-size:12px;color:var(--ink-soft);margin-top:3px;display:flex;gap:9px;flex-wrap:wrap;align-items:center}
.chan{font-size:11px;font-weight:500;padding:2px 8px;border-radius:6px;background:#eef3f9;color:#34536f}
.nopost{padding:14px 16px;font-size:13px;color:var(--ink-soft);background:#f7f9fc;border-radius:10px;border:1px dashed var(--line)}
.checklist{margin-top:16px;background:#f7f9fc;border:1px solid var(--line);border-radius:12px;padding:14px 16px}
.checklist h4{margin:0 0 9px;font-size:13px}
.check{display:flex;align-items:center;gap:9px;font-size:13px;padding:3px 0}
.check .box{width:16px;height:16px;border-radius:5px;border:2px solid var(--grey);flex:none;display:grid;place-items:center;font-size:11px;color:#fff}
.check.done .box{background:var(--green);border-color:var(--green)}.check.done .lbl{color:var(--ink-soft);text-decoration:line-through}
.check.todo .box{border-color:var(--amber)}
.links{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}
.btn{font-family:inherit;font-size:13px;font-weight:500;padding:9px 14px;border-radius:9px;border:1px solid var(--line);background:#fff;color:var(--flik-blue);cursor:pointer;display:inline-flex;align-items:center;gap:7px}
.btn:hover{background:#f1f6fc;text-decoration:none}.btn[disabled]{opacity:.45;cursor:not-allowed}
footer{color:var(--ink-soft);font-size:12.5px;text-align:center;padding:24px 20px 40px}
.btn.solid{background:var(--flik-blue);color:#fff;border-color:var(--flik-blue)}
.btn.solid:hover{background:var(--flik-blue-dark)}
.btn.sm{padding:6px 11px;font-size:12px}
.headbtn{position:absolute;top:20px;right:60px;background:rgba(255,255,255,.18);border:none;color:#fff;font-family:inherit;font-size:12.5px;font-weight:500;padding:7px 13px;border-radius:9px;cursor:pointer}
.headbtn:hover{background:rgba(255,255,255,.32)}
.linkchips{display:flex;gap:10px;flex-wrap:wrap;margin:0 0 20px}
.linkchip{display:inline-flex;align-items:center;gap:8px;background:#f1f6fc;border:1px solid #dbe7f5;border-radius:11px;padding:10px 14px;font-size:13px;color:var(--ink)}
.linkchip b{color:var(--flik-blue);font-weight:600}
.linkchip:hover{background:#e7f0fb;text-decoration:none}
.planhead{display:flex;align-items:center;justify-content:space-between;gap:10px;margin:6px 0 12px;flex-wrap:wrap}
.planttl{font-size:15.5px;font-weight:700}
.planbtns{display:flex;gap:8px}
.tasklist{display:flex;flex-direction:column;gap:8px;margin-bottom:22px}
.taskitem{border:1px solid var(--line);border-radius:12px;background:#fff;overflow:hidden;transition:box-shadow .15s,border-color .15s}
.taskitem.open{box-shadow:var(--shadow);border-color:#dbe7f5}
.taskrow{display:grid;grid-template-columns:16px 62px minmax(0,1fr) auto auto;gap:12px;align-items:center;padding:13px 15px;cursor:pointer}
.taskrow:hover{background:#f7faff}
.caret{color:var(--grey);font-size:11px;transition:transform .15s;display:inline-block}
.taskitem.open .caret{transform:rotate(90deg)}
.tdate{font-size:12px;color:var(--ink-soft);font-weight:500;white-space:nowrap}
.tlabel{font-weight:500;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tchan{font-size:11px;color:var(--ink-soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:170px;text-align:right}
.taskdetail{display:none;padding:2px 15px 15px}
.taskitem.open .taskdetail{display:block}
.taskactions{display:flex;gap:7px;align-items:center;margin-top:12px;flex-wrap:wrap}
@media(max-width:880px){.taskrow{grid-template-columns:16px 52px 1fr auto;gap:9px}.tchan{display:none}}
form.f label{display:block;font-size:12.5px;color:var(--ink-soft);margin:18px 0 6px;font-weight:500}
form.f input,form.f select,form.f textarea{width:100%;font-family:inherit;font-size:15px;padding:12px 14px;border:1px solid #e6ebf2;border-radius:11px;background:#fbfcfe;color:var(--ink);transition:border-color .12s,box-shadow .12s,background .12s}
form.f textarea{resize:vertical;min-height:150px;line-height:1.6}
.postbody{margin-top:8px;background:#f7f9fc;border:1px solid var(--line);border-radius:8px;padding:11px 13px;font-size:13.5px;line-height:1.55;white-space:pre-wrap}
form.f input:focus,form.f select:focus,form.f textarea:focus{outline:none;border-color:var(--flik-blue);background:#fff;box-shadow:0 0 0 3px rgba(0,82,155,.12)}
form.f .two{display:grid;grid-template-columns:1fr 1fr;gap:16px 24px}
form.f .actions{margin-top:24px;padding-top:18px;border-top:1px solid var(--line);display:flex;gap:10px;justify-content:flex-end}
form.f .actions .btn{padding:11px 22px;font-size:14px}
@media(max-width:880px){form.f .two{grid-template-columns:1fr}}
@media(max-width:880px){.stats{grid-template-columns:repeat(2,1fr)}.grid2,.wheelwrap{grid-template-columns:1fr}.fieldgrid{grid-template-columns:1fr}}
</style>
@endverbatim
</head>
<body>
<div class="topbar">
  <div class="wrap">
    <div class="brand">
      <span class="logosvg"></span>
      <div>
        <h1>Vivu Planner</h1>
        <div class="sub">{{ $company->name ?? 'Årshjul' }}</div>
      </div>
    </div>
    <div class="spacer"></div>
    <div class="userchip"><span>{{ $user->name }}</span></div>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button class="logoutbtn" type="submit">Logg ut</button>
    </form>
  </div>
</div>
<div class="tabbar">
  <div class="tabs">
    <button class="tab active" data-view="dash">Dashboard</button>
    <button class="tab" data-view="wheel">Årshjul</button>
    <button class="tab" data-view="list">Eventliste</button>
  </div>
</div>

<main class="wrap">
  <section class="view active" id="view-dash">
    <div class="viewhead"><h2>Dashboard 2026</h2><button class="btn solid" onclick="openEventForm()">＋ Nytt arrangement</button></div>
    <div class="stats" id="statRow"></div>
    <div class="grid2">
      <div class="panel"><h3>Haster nå – trenger arbeid <span class="tag" id="urgCount">0</span></h3><div class="body" id="urgentList"></div></div>
      <div class="panel"><h3>Neste arrangement <span class="tag cool">kommende</span></h3><div class="body" id="upcomingList"></div></div>
    </div>
  </section>
  <section class="view" id="view-wheel">
    <div class="viewhead"><h2>Årshjul 2026</h2><button class="btn solid" onclick="openEventForm()">＋ Nytt arrangement</button></div>
    <div class="note">Klikk en prikk i hjulet eller en idrett i forklaringen for å filtrere. 🔁 = årlig gjentakende.</div>
    <div class="wheelwrap">
      <div class="wheelcard"><div id="wheelHost"></div><div class="wheel-hint" id="wheelHint"></div></div>
      <div class="legend" id="legend"></div>
    </div>
  </section>
  <section class="view" id="view-list">
    <div class="viewhead"><h2>Eventliste</h2><button class="btn solid" onclick="openEventForm()">＋ Nytt arrangement</button></div>
    <div class="toolbar">
      <input type="text" id="search" placeholder="Søk etter arrangement…">
      <select id="fSport"><option value="">Alle idretter</option></select>
      <select id="fStatus"><option value="">Alle statuser</option></select>
      <button class="btn" id="archiveBtn" onclick="toggleArchive()">📁 Vis gjennomførte</button>
    </div>
    <div id="listHost"></div>
  </section>
</main>

<footer>Vivu Planner · live på ekte data · Farsund og Lista Idrettsklubb · Havdur Design</footer>
<div class="overlay" id="overlay"><div class="modal" id="modal"></div></div>

<script>
window.DATA = @json($events);
window.ME = @json(['name' => $user->name]);
window.CATS = @json($categories);
window.MEMBERS = @json($members);
window.DESTS = @json($destinations);
window.CSRF = '{{ csrf_token() }}';
</script>
@verbatim
<script>
const LOGO='<svg class="brandmark" viewBox="0 0 191.4 365" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M191.4,121.5V45l-11.7,3.6C95.2,74.9,43.6,106.1,26.6,141.5c-8,16.5-8.6,33.7-1.8,51.1c5.3,13.7,14.4,23.2,27.1,28.2c-9.8,65.7,7.5,134.3,8.3,137.4l1.7,6.8h65.7l-1.9-10.6c-1.1-6.1-2.3-12.1-3.5-17.9c-5.8-28.4-10.3-50.9,0.6-66.9c9-13.2,29-22.4,61.3-28.1l7.4-1.3l0-81.4l-13.9,9.2C162,178,124.8,198.7,92.8,205c-2.7,5.8-4.4,12.5-5.4,19.1c15.4-1.9,33.2-7.3,53.2-16.2c12.9-5.7,24.4-11.9,32.8-16.7v33.7c-33.4,6.9-54.4,17.8-65.6,34.3c-15.2,22.3-9.4,50.6-3.4,80.6c0.5,2.3,0.9,4.6,1.4,6.9H76c-3.7-17.2-12.4-64.3-7.8-111.3c0.6-9.7,1.9-20,4.9-29.5c17.1-58.2,110.6-82.7,111.5-83L191.4,121.5z M173.4,107.7c-9.9,3-29.2,9.7-49.6,20.7c-37,20-60.6,45.6-68.4,74.2c-6.3-3.4-10.9-8.8-13.8-16.5c-5-12.8-4.6-24.8,1.2-36.8c17.2-35.5,78.2-62.5,130.6-79.8V107.7z"/><path fill="currentColor" d="M41.8,83.6c-5.7,0-11.5-1.2-16.9-3.6C14.7,75.5,6.9,67.3,2.8,56.9s-3.8-21.8,0.7-32S16.3,6.9,26.7,2.8c21.5-8.3,45.7,2.4,54.1,23.9c8.3,21.5-2.4,45.7-23.9,54.1C52,82.7,46.9,83.6,41.8,83.6z M41.8,18c-2.9,0-5.8,0.5-8.6,1.6c-5.9,2.3-10.6,6.8-13.2,12.6c-2.6,5.8-2.7,12.3-0.4,18.2c2.3,5.9,6.8,10.6,12.6,13.2c5.8,2.6,12.3,2.7,18.2,0.4C62.6,59.2,68.7,45.4,64,33.2C60.3,23.8,51.3,18,41.8,18z"/></svg>';
document.querySelector('.logosvg').innerHTML=LOGO;

const DATA=window.DATA||[];
const TODAY=new Date();
const MONTHS=['Januar','Februar','Mars','April','Mai','Juni','Juli','August','September','Oktober','November','Desember'];
const MS=['jan','feb','mar','apr','mai','jun','jul','aug','sep','okt','nov','des'];
const col=e=>e.color||'#8795a3';
const fmt=d=>{if(!d)return '';const x=new Date(d);return x.getDate()+'. '+MS[x.getMonth()];};
const daysTo=d=>Math.round((new Date(d)-TODAY)/86400000);
const initials=n=>(n||'?').split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();

function eventState(e){
  const dt=new Date(e.date);
  if(e.approval==='Internt'||/ikke markedsf|ikke skal publis/i.test(e.notat||''))return{key:'Internt',cls:'st-avlyst'};
  if(dt<TODAY)return{key:'Gjennomført',cls:'st-publisert'};
  if((!e.posts||!e.posts.length)&&e.type!=='Administrasjon')return{key:'Mangler innhold',cls:'st-mangler'};
  if((e.posts||[]).some(p=>p.status==='Under arbeid'))return{key:'Under arbeid',cls:'st-arbeid'};
  return{key:'Planlagt',cls:'st-planlagt'};
}
function approvalPill(e){
  if(e.approval==='Godkjent')return '<span class="pill st-godkjent">✓ Godkjent</span>';
  if(e.approval==='Til godkjenning')return '<span class="pill st-tilgodkj">⏳ Til godkjenning</span>';
  return '';
}

/* user chip */
document.querySelector('.userchip').insertAdjacentHTML('beforeend','<span class="av">'+initials(window.ME.name)+'</span>');

/* DASHBOARD */
function renderStats(){
  const totalPosts=DATA.reduce((a,e)=>a+(e.posts?e.posts.length:0),0);
  const upcoming=DATA.filter(e=>new Date(e.date)>=TODAY);
  const needsWork=upcoming.filter(e=>eventState(e).key==='Mangler innhold').length;
  const toApprove=DATA.filter(e=>e.approval==='Til godkjenning').length;
  document.getElementById('statRow').innerHTML=[
    {c:'b1',n:DATA.length,l:'Arrangement i årshjulet'},
    {c:'b2',n:totalPosts,l:'Planlagte oppgaver'},
    {c:'b3',n:needsWork,l:'Kommende uten innhold'},
    {c:'b4',n:toApprove,l:'Venter på godkjenning'}
  ].map(s=>'<div class="stat '+s.c+'"><div class="bar"></div><div class="n">'+s.n+'</div><div class="l">'+s.l+'</div></div>').join('');
}
function renderUrgent(){
  let items=[];
  DATA.forEach(e=>(e.posts||[]).forEach(p=>{if(!p.date)return;const dd=daysTo(p.date);if(dd>=-3&&dd<=60&&p.status!=='Publisert')items.push({e,p,dd});}));
  DATA.filter(e=>{const dd=daysTo(e.date);return dd>=0&&dd<=70&&(!e.posts||!e.posts.length)&&e.type!=='Administrasjon'&&!/ikke markedsf|ikke skal/i.test(e.notat||'');})
    .forEach(e=>items.push({e,p:null,dd:daysTo(e.date),missing:true}));
  items.sort((a,b)=>a.dd-b.dd);
  document.getElementById('urgCount').textContent=items.length;
  if(!items.length){document.getElementById('urgentList').innerHTML='<div class="row" style="cursor:default"><div class="meta"><div class="s">Ingenting forfaller de neste ukene 🎉</div></div></div>';return;}
  document.getElementById('urgentList').innerHTML=items.map(i=>{
    const when=i.dd<0?'forsinket':(i.dd===0?'i dag':'om '+i.dd+' d'),cls=i.dd<=7?'urgent':(i.dd<=21?'soon':'');
    const label=i.missing?'Mangler oppgaver & tekst':(i.p.label||'Innlegg');
    return '<div class="row" onclick="openEvent('+i.e.id+')"><span class="dot" style="background:'+col(i.e)+'"></span>'+
      '<div class="meta"><div class="t">'+i.e.title+'</div><div class="s">'+label+' · '+i.e.sport+'</div></div>'+
      '<div class="when"><span class="pill '+(i.missing?'st-mangler':'st-arbeid')+'">'+(i.missing?'mangler':i.p.status)+'</span><div class="d '+cls+'">'+(i.p?fmt(i.p.date):fmt(i.e.date))+' · '+when+'</div></div></div>';
  }).join('');
}
function renderUpcoming(){
  const up=DATA.filter(e=>new Date(e.date)>=TODAY).sort((a,b)=>new Date(a.date)-new Date(b.date)).slice(0,8);
  document.getElementById('upcomingList').innerHTML=up.map(e=>{
    const st=eventState(e),dd=daysTo(e.date);
    return '<div class="row" onclick="openEvent('+e.id+')"><span class="dot" style="background:'+col(e)+'"></span>'+
      '<div class="meta"><div class="t">'+e.title+' '+(e.recur==='yearly'?'<span class="recur">🔁</span>':'')+'</div><div class="s">'+e.sport+' · '+(e.posts?e.posts.length:0)+' oppgaver</div></div>'+
      '<div class="when"><span class="pill '+st.cls+'">'+st.key+'</span><div class="d">'+fmt(e.date)+' · om '+dd+' d</div></div></div>';
  }).join('')||'<div class="row" style="cursor:default"><div class="meta"><div class="s">Ingen flere arrangement i 2026.</div></div></div>';
}

/* WHEEL */
let hidden=new Set();
function renderWheel(){
  const evs=DATA;
  document.getElementById('wheelHint').textContent='FLIK Årshjul 2026 · '+evs.length+' arrangement';
  const size=560,cx=280,cy=280,rO=250,rI=120;
  let svg='<svg class="wheel" viewBox="0 0 '+size+' '+size+'" xmlns="http://www.w3.org/2000/svg">';
  for(let m=0;m<12;m++){
    const a0=(m*30-90)*Math.PI/180,a1=((m+1)*30-90)*Math.PI/180;
    const x0=cx+rO*Math.cos(a0),y0=cy+rO*Math.sin(a0),x1=cx+rO*Math.cos(a1),y1=cy+rO*Math.sin(a1);
    const xi0=cx+rI*Math.cos(a0),yi0=cy+rI*Math.sin(a0),xi1=cx+rI*Math.cos(a1),yi1=cy+rI*Math.sin(a1);
    svg+='<path d="M'+xi0+' '+yi0+' L'+x0+' '+y0+' A'+rO+' '+rO+' 0 0 1 '+x1+' '+y1+' L'+xi1+' '+yi1+' A'+rI+' '+rI+' 0 0 0 '+xi0+' '+yi0+' Z" fill="'+(m%2?'#eef3f9':'#f7fafd')+'" stroke="#dde5ee"/>';
    const am=((m+0.5)*30-90)*Math.PI/180;
    svg+='<text x="'+(cx+(rO+18)*Math.cos(am))+'" y="'+(cy+(rO+18)*Math.sin(am))+'" font-size="12" font-weight="500" fill="#5b6b7b" text-anchor="middle" dominant-baseline="middle" font-family="Ubuntu">'+MS[m].toUpperCase()+'</text>';
  }
  svg+='<circle cx="'+cx+'" cy="'+cy+'" r="'+(rI-6)+'" fill="#1c3155"/>';
  svg+='<text x="'+cx+'" y="'+(cy-10)+'" font-size="30" font-weight="700" fill="#fff" text-anchor="middle" font-family="Ubuntu">FLIK</text>';
  svg+='<text x="'+cx+'" y="'+(cy+14)+'" font-size="13" fill="#cfe0f2" text-anchor="middle" font-family="Ubuntu">Årshjul 2026</text>';
  const byMonth={};evs.forEach(e=>{const m=new Date(e.date).getMonth();(byMonth[m]=byMonth[m]||[]).push(e);});
  Object.keys(byMonth).forEach(m=>{
    const list=byMonth[m].filter(e=>!hidden.has(e.sport));
    list.forEach((e,i)=>{
      const n=list.length;
      const am=((+m+(n===1?0.5:(0.18+0.64*i/Math.max(1,n-1))))*30-90)*Math.PI/180;
      const rr=rI+30+(i%3)*30;
      const px=cx+rr*Math.cos(am),py=cy+rr*Math.sin(am);
      svg+='<circle cx="'+px+'" cy="'+py+'" r="8.5" fill="'+col(e)+'" stroke="#fff" stroke-width="2" style="cursor:pointer" onclick="openEvent('+e.id+')"><title>'+e.title+' · '+fmt(e.date)+'</title></circle>';
    });
  });
  svg+='</svg>';
  document.getElementById('wheelHost').innerHTML=svg;
}
function renderLegend(){
  const counts={},colors={};
  DATA.forEach(e=>{counts[e.sport]=(counts[e.sport]||0)+1;colors[e.sport]=col(e);});
  document.getElementById('legend').innerHTML='<h4>Idretter / grupper</h4>'+Object.keys(counts).sort().map(s=>
    '<div class="item '+(hidden.has(s)?'off':'')+'" onclick="toggleSport(\''+s.replace(/'/g,"\\'")+'\')"><span class="sw" style="background:'+colors[s]+'"></span>'+s+'<span class="c">'+counts[s]+'</span></div>').join('');
}
function toggleSport(s){hidden.has(s)?hidden.delete(s):hidden.add(s);renderWheel();renderLegend();}

/* LIST */
function populateFilters(){
  const sp=document.getElementById('fSport');
  [...new Set(DATA.map(e=>e.sport))].sort().forEach(s=>sp.insertAdjacentHTML('beforeend','<option>'+s+'</option>'));
  ['Mangler innhold','Under arbeid','Planlagt','Gjennomført','Internt'].forEach(s=>document.getElementById('fStatus').insertAdjacentHTML('beforeend','<option>'+s+'</option>'));
}
function renderList(){
  const q=document.getElementById('search').value.toLowerCase(),fs=document.getElementById('fSport').value,fst=document.getElementById('fStatus').value;
  const fom=new Date(TODAY.getFullYear(),TODAY.getMonth(),1);
  let evs=DATA.filter(e=>{
    if(!showArchive&&new Date(e.date)<fom)return false;
    if(fs&&e.sport!==fs)return false;if(fst&&eventState(e).key!==fst)return false;
    if(q&&!((e.title+' '+(e.desc||'')+' '+e.sport).toLowerCase().includes(q)))return false;return true;
  }).slice().sort((a,b)=>new Date(a.date)-new Date(b.date));
  const groups={};evs.forEach(e=>{const m=new Date(e.date).getMonth();(groups[m]=groups[m]||[]).push(e);});
  let html='';
  Object.keys(groups).sort((a,b)=>a-b).forEach(m=>{
    html+='<div class="monthgroup"><h3>'+MONTHS[m]+' 2026</h3><div class="elist">';
    groups[m].forEach(e=>{
      const st=eventState(e),d=new Date(e.date);
      html+='<div class="erow" onclick="openEvent('+e.id+')">'+
        '<div class="date"><span class="day">'+d.getDate()+'</span>'+MS[m]+'</div>'+
        '<div class="ti"><span class="sport" style="background:'+col(e)+'">'+e.sport+'</span><span class="nm">'+e.title+'</span> '+(e.recur==='yearly'?'<span class="recur">🔁</span>':'')+'</div>'+
        '<div class="muted">'+((e.posts&&e.posts.length)?e.posts.length+' oppgaver':'—')+'</div>'+
        '<span class="pill '+st.cls+'">'+st.key+'</span></div>';
    });
    html+='</div></div>';
  });
  document.getElementById('listHost').innerHTML=html||'<div class="nopost" style="margin:0">Ingen treff.</div>';
}

/* EVENT CARD */
function openEvent(id){
  const e=DATA.find(x=>x.id===id);if(!e)return;
  const c=col(e);
  const f=(k,v)=>'<div class="field"><div class="k">'+k+'</div><div class="v '+(v?'':'empty')+'">'+(v||'mangler')+'</div></div>';
  const checks=[['Dato satt',!!e.date],['Ansvarlig tildelt',!!e.ansvarlig],['Landingsside',!!e.landing],['Påmelding (Hoopit)',!!e.hoopit],['Oppgaver planlagt',!!(e.posts&&e.posts.length)]];
  let postsHtml;
  if(e.posts&&e.posts.length){
    postsHtml='<div class="tasklist">'+e.posts.map(p=>{
      const pages=(p.pages||[]).join(' · ');
      const stCls=p.status==='Under arbeid'?'st-arbeid':(p.status==='Publisert'?'st-publisert':(p.status==='Klar for publisering'?'st-klar':'st-planlagt'));
      return '<div class="taskitem" id="ti'+p.id+'">'+
        '<div class="taskrow" onclick="toggleTask('+p.id+')">'+
          '<span class="caret">▸</span>'+
          '<span class="tdate">'+(p.date?fmt(p.date):'—')+'</span>'+
          '<span class="tlabel">'+(p.label||'Innlegg')+'</span>'+
          '<span class="tchan">'+pages+'</span>'+
          '<span class="pill '+stCls+'">'+p.status+'</span>'+
        '</div>'+
        '<div class="taskdetail">'+
          (p.body?'<div class="postbody">'+esc(p.body)+'</div>':'<div class="muted" style="padding:6px 0 2px">Ingen tekst lagt inn ennå.</div>')+
          (p.text?'<div style="margin-top:8px;font-size:12.5px"><a href="'+p.text+'" target="_blank">🔗 Lenke ↗</a></div>':'')+
          '<div class="taskactions">'+
            '<button class="btn sm" onclick="openTaskForm('+e.id+','+p.id+')">✎ Rediger</button>'+
            (p.body?'<button class="btn sm" onclick="copyText(this,'+e.id+','+p.id+')">📋 Kopier tekst</button>':'')+
            '<span style="flex:1"></span>'+
            '<button class="btn sm" title="Flytt opp" onclick="moveTask('+e.id+','+p.id+',-1)">↑</button>'+
            '<button class="btn sm" title="Flytt ned" onclick="moveTask('+e.id+','+p.id+',1)">↓</button>'+
            '<button class="btn sm" style="color:#b23535" title="Slett" onclick="deleteTask('+e.id+','+p.id+')">🗑</button>'+
          '</div>'+
        '</div>'+
      '</div>';
    }).join('')+'</div>';
  }else{
    postsHtml='<div class="nopost">Ingen oppgaver planlagt ennå. Bruk «🪄 Foreslå plan» for et komplett forslag basert på datoen.</div>';
  }
  const chips=
    (e.landing?'<a class="linkchip" href="'+e.landing+'" target="_blank">🌐 Landingsside <b>Åpne ↗</b></a>':'')+
    (e.hoopit?'<a class="linkchip" href="'+e.hoopit+'" target="_blank">📝 Hoopit påmelding <b>Åpne ↗</b></a>':'');
  document.getElementById('modal').innerHTML=
    '<div class="head" style="background:linear-gradient(135deg,'+c+','+c+' 55%,rgba(0,0,0,.35) 160%)">'+
      '<button class="close" onclick="closeModal()">×</button>'+
      '<button class="headbtn" onclick="openEventForm('+e.id+')">✎ Rediger</button>'+
      '<div class="idtag">'+e.type+(e.recur==='yearly'?' · 🔁 årlig':'')+'</div>'+
      '<h2>'+e.title+'</h2>'+(e.desc?'<div class="sub">'+e.desc+'</div>':'')+
      '<div style="margin-top:10px">'+approvalPill(e)+'</div></div>'+
    '<div class="mbody"><div class="fieldgrid">'+
      f('Dato',e.date?fmt(e.date)+' 2026':'')+f('Idrett / gruppe',e.sport)+f('Hovedmål',e.mal)+f('Ansvarlig',e.ansvarlig)+'</div>'+
      (chips?'<div class="linkchips">'+chips+'</div>':'')+
      (e.notat?'<div class="note" style="margin:0 0 18px">📝 '+e.notat+'</div>':'')+
      (e.brief?'<div class="brief">📋 <b>Praktisk info:</b> '+esc(e.brief)+'</div>':'')+
      '<div class="planhead"><div class="planttl">📅 Publiseringsplan <span class="count">'+(e.posts?e.posts.length:0)+' oppgaver</span></div>'+
        '<div class="planbtns"><button class="btn solid sm" onclick="generatePlan('+e.id+')">🪄 Foreslå plan</button><button class="btn sm" onclick="openTaskForm('+e.id+',null)">＋ Oppgave</button></div></div>'+
      postsHtml+
      '<div class="checklist"><h4>Sjekkliste – klar for publisering?</h4>'+checks.map(ch=>'<div class="check '+(ch[1]?'done':'todo')+'"><span class="box">'+(ch[1]?'✓':'')+'</span><span class="lbl">'+ch[0]+'</span></div>').join('')+'</div>'+
      '<div class="links">'+
        '<button class="btn" onclick="duplicateNextYear('+e.id+')">📅 Dupliser til neste år</button>'+
        '<button class="btn" style="color:#b23535" onclick="deleteEvent('+e.id+')">🗑 Slett</button>'+
      '</div></div>';
  document.getElementById('overlay').classList.add('open');
}
function toggleTask(id){const it=document.getElementById('ti'+id);if(it)it.classList.toggle('open');}
function closeModal(){document.getElementById('overlay').classList.remove('open');}
document.getElementById('overlay').addEventListener('click',e=>{if(e.target.id==='overlay')closeModal();});
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeModal();});

/* TABS */
document.querySelectorAll('.tab').forEach(t=>t.addEventListener('click',()=>{
  document.querySelectorAll('.tab').forEach(x=>x.classList.remove('active'));
  document.querySelectorAll('.view').forEach(x=>x.classList.remove('active'));
  t.classList.add('active');document.getElementById('view-'+t.dataset.view).classList.add('active');
  if(t.dataset.view==='wheel'){renderWheel();renderLegend();}
  if(t.dataset.view==='list')renderList();
}));
['search','fSport','fStatus'].forEach(id=>document.getElementById(id).addEventListener('input',renderList));

/* ---- REDIGERING (CRUD via fetch) ---- */
const CSRF=window.CSRF, CATS=window.CATS||[], MEMBERS=window.MEMBERS||[], DESTS=window.DESTS||[];
function esc(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');}
function val(id){const el=document.getElementById(id);return el?el.value.trim():'';}
async function api(method,url,body){
  const r=await fetch(url,{method,headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},body:body?JSON.stringify(body):undefined});
  if(r.status===422){const j=await r.json();throw new Error(Object.values(j.errors||{}).flat().join('\n')||'Ugyldige data');}
  if(!r.ok)throw new Error('Noe gikk galt ('+r.status+'). Er du logget inn?');
  return r.status===204?null:r.json();
}
function upsert(card){const i=DATA.findIndex(x=>x.id===card.id);if(i>=0)DATA[i]=card;else DATA.push(card);}
function rerender(){renderStats();renderUrgent();renderUpcoming();const a=document.querySelector('.tab.active');if(a&&a.dataset.view==='wheel'){renderWheel();renderLegend();}if(a&&a.dataset.view==='list')renderList();}

function openEventForm(id){
  const e=id?DATA.find(x=>x.id===id):{};
  const sel=(v,o)=>String(v)===String(o)?' selected':'';
  const catOpts='<option value="">– ingen –</option>'+CATS.map(c=>'<option value="'+c.id+'"'+sel(e.category_id,c.id)+'>'+c.name+'</option>').join('');
  const memOpts='<option value="">– velg –</option>'+MEMBERS.map(m=>'<option value="'+m.id+'"'+sel(e.responsible_user_id,m.id)+'>'+m.name+'</option>').join('');
  const typeOpts=['Event','Turnering','Rekruttering','Administrasjon'].map(t=>'<option'+sel(e.type,t)+'>'+t+'</option>').join('');
  const malOpts=['','Rekruttering','Konkurranse','Aktivitet','Inkludering','Fellesskap','Økonomi','Admin'].map(t=>'<option'+sel(e.mal||'',t)+'>'+(t||'– velg –')+'</option>').join('');
  const apprOpts=[['utkast','Utkast'],['til_godkjenning','Til godkjenning'],['godkjent','Godkjent'],['internt','Internt']].map(o=>'<option value="'+o[0]+'"'+sel(e.approval_status||'utkast',o[0])+'>'+o[1]+'</option>').join('');
  document.getElementById('modal').innerHTML=
    '<div class="head" style="background:linear-gradient(135deg,var(--flik-blue),var(--flik-blue-deep))"><button class="close" onclick="closeModal()">×</button><h2>'+(id?'Rediger arrangement':'Nytt arrangement')+'</h2><div class="sub">Plasseres automatisk i årshjul og liste</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveEventForm(event,'+(id||'null')+')">'+
      '<label>Tittel *</label><input id="f_title" required value="'+esc(e.title)+'" placeholder="f.eks. Tine Fotballskole">'+
      '<div class="two"><div><label>Idrett / gruppe</label><select id="f_cat">'+catOpts+'</select></div>'+
      '<div><label>Dato *</label><input id="f_date" type="date" required value="'+(e.date||'2026-08-21')+'"></div></div>'+
      '<div class="two"><div><label>Type</label><select id="f_type">'+typeOpts+'</select></div>'+
      '<div><label>Hovedmål</label><select id="f_goal">'+malOpts+'</select></div></div>'+
      '<div class="two"><div><label>Ansvarlig</label><select id="f_resp">'+memOpts+'</select></div>'+
      '<div><label>Gjentakelse</label><select id="f_recur"><option value="yearly"'+sel(e.recur,'yearly')+'>🔁 Årlig</option><option value="none"'+sel(e.recur,'none')+'>Engangs</option></select></div></div>'+
      '<div class="two"><div><label>Status</label><select id="f_appr">'+apprOpts+'</select></div><div></div></div>'+
      '<label>Beskrivelse</label><input id="f_desc" value="'+esc(e.desc)+'">'+
      '<label>Notat (intern)</label><input id="f_notat" value="'+esc(e.notat)+'">'+
      '<label>Praktisk info / stikkord <span style="font-weight:400;color:var(--ink-soft)">– grunnlag for AI-tekst</span></label>'+
      '<textarea id="f_brief" placeholder="F.eks. datoer, hva barna får (t-skjorte, ball), hva de må ha med, pris, sted, tider… Kan være stikkord.">'+esc(e.brief)+'</textarea>'+
      '<div class="two"><div><label>Landingsside</label><input id="f_land" value="'+esc(e.landing)+'" placeholder="https://flik.no/…"></div>'+
      '<div><label>Påmelding (Hoopit)</label><input id="f_hoop" value="'+esc(e.hoopit)+'"></div></div>'+
      '<div class="actions"><button type="button" class="btn" onclick="'+(id?'openEvent('+id+')':'closeModal()')+'">Avbryt</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
}
async function saveEventForm(ev,id){ev.preventDefault();
  const body={title:val('f_title'),category_id:val('f_cat')||null,type:val('f_type'),goal:val('f_goal')||null,event_date:val('f_date'),recurrence:val('f_recur'),approval_status:val('f_appr'),description:val('f_desc')||null,internal_note:val('f_notat')||null,brief:val('f_brief')||null,landing_url:val('f_land')||null,signup_url:val('f_hoop')||null,responsible_user_id:val('f_resp')||null};
  try{const card=id?await api('PUT','/events/'+id,body):await api('POST','/events',body);upsert(card);rerender();openEvent(card.id);}catch(err){alert(err.message);}
}
async function deleteEvent(id){
  if(!confirm('Slette dette arrangementet? Dette kan ikke angres.'))return;
  try{await api('DELETE','/events/'+id);const i=DATA.findIndex(x=>x.id===id);if(i>=0)DATA.splice(i,1);rerender();closeModal();}catch(err){alert(err.message);}
}

function openTaskForm(eventId,taskId){
  const e=DATA.find(x=>x.id===eventId);const t=(taskId?(e.posts||[]).find(p=>p.id===taskId):{})||{};
  const sel=(v,o)=>v===o?' selected':'';
  const stOpts=[['planlagt','Planlagt'],['under_arbeid','Under arbeid'],['klar','Klar for publisering'],['publisert','Publisert']].map(o=>'<option value="'+o[0]+'"'+sel(t.status_raw||'planlagt',o[0])+'>'+o[1]+'</option>').join('');
  const dsel=(t.destination_ids||[]);
  const destOpts=DESTS.map(d=>'<option value="'+d.id+'"'+(dsel.indexOf(d.id)>=0?' selected':'')+'>'+d.name+'</option>').join('');
  document.getElementById('modal').innerHTML=
    '<div class="head" style="background:linear-gradient(135deg,var(--flik-blue),var(--flik-blue-deep))"><button class="close" onclick="closeModal()">×</button><h2>'+(taskId?'Rediger oppgave':'Ny oppgave')+'</h2><div class="sub">Innlegg i publiseringsplanen</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveTaskForm(event,'+eventId+','+(taskId||'null')+')">'+
      '<div class="two"><div><label>Hva slags post *</label><input id="t_label" required value="'+esc(t.label)+'" placeholder="f.eks. Teaser – hold av datoen"></div>'+
      '<div><label>Publiseringsdato</label><input id="t_date" type="date" value="'+(t.date||'')+'"></div></div>'+
      '<label>Status</label><select id="t_status">'+stOpts+'</select>'+
      '<label>FLIK-side(r) / destinasjoner</label><select id="t_dests" multiple size="5" style="height:auto">'+destOpts+'</select>'+
      '<label style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">Tekst til innlegget<span style="display:flex;gap:6px">'+(t.body?'<button type="button" class="btn sm" id="aiReviseBtn" onclick="suggestText('+eventId+',true)">🔄 Oppdater for nytt år</button>':'')+'<button type="button" class="btn sm" id="aiBtn" onclick="suggestText('+eventId+',false)">✨ Foreslå tekst</button></span></label>'+
      '<textarea id="t_body" placeholder="Skriv eller la AI foreslå teksten. Denne kan kopieres rett ut til Facebook / Meta Planner.">'+esc(t.body)+'</textarea>'+
      '<label>Lenke (valgfritt – f.eks. Google Doc)</label><input id="t_text" value="'+esc(t.text)+'">'+
      '<div class="actions"><button type="button" class="btn" onclick="openEvent('+eventId+')">Avbryt</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
}
async function saveTaskForm(ev,eventId,taskId){ev.preventDefault();
  const dests=[...document.getElementById('t_dests').selectedOptions].map(o=>parseInt(o.value,10));
  const body={label:val('t_label'),publish_date:val('t_date')||null,status:val('t_status'),draft_url:val('t_text')||null,body_draft:val('t_body')||null,destination_ids:dests};
  try{const card=taskId?await api('PUT','/tasks/'+taskId,body):await api('POST','/events/'+eventId+'/tasks',body);upsert(card);rerender();openEvent(eventId);}catch(err){alert(err.message);}
}
async function deleteTask(eventId,taskId){
  if(!confirm('Slette denne oppgaven?'))return;
  try{const card=await api('DELETE','/tasks/'+taskId);upsert(card);rerender();openEvent(eventId);}catch(err){alert(err.message);}
}

/* arkiv-bryter for eventlisten */
let showArchive=false;
function toggleArchive(){showArchive=!showArchive;const b=document.getElementById('archiveBtn');if(b)b.textContent=showArchive?'📁 Skjul gjennomførte':'📁 Vis gjennomførte';renderList();}

/* foreslå publiseringsplan ut fra eventdato */
async function generatePlan(eventId){
  const e=DATA.find(x=>x.id===eventId);
  const msg=(e.posts||[]).length?'Legge til en foreslått publiseringsplan i tillegg til de eksisterende oppgavene?':'Lage forslag til publiseringsplan basert på eventdatoen?';
  if(!confirm(msg))return;
  try{const card=await api('POST','/events/'+eventId+'/generate-plan');upsert(card);rerender();openEvent(eventId);}catch(err){alert(err.message);}
}

/* kopier event til neste år (datoer flyttet ett år frem) */
async function duplicateNextYear(eventId){
  const e=DATA.find(x=>x.id===eventId);
  const ny=(new Date(e.date)).getFullYear()+1;
  if(!confirm('Kopiere «'+e.title+'» til '+ny+'? Oppgaver og tekster kopieres, med alle datoer flyttet ett år frem.'))return;
  try{
    const card=await api('POST','/events/'+eventId+'/duplicate-next-year');
    upsert(card);rerender();openEvent(card.id);
    alert('Kopiert til '+ny+'! Åpne hver oppgave og bruk «🔄 Oppdater for nytt år», så retter AI årstall og årsklasser i teksten.');
  }catch(err){alert(err.message);}
}

/* flytt oppgave opp/ned (lagrer rekkefølge) */
async function moveTask(eventId,taskId,dir){
  const e=DATA.find(x=>x.id===eventId);const order=(e.posts||[]).map(p=>p.id);
  const i=order.indexOf(taskId);const j=i+dir;if(i<0||j<0||j>=order.length)return;
  const t=order[i];order[i]=order[j];order[j]=t;
  try{const card=await api('POST','/events/'+eventId+'/reorder-tasks',{order});upsert(card);rerender();openEvent(eventId);const it=document.getElementById('ti'+taskId);if(it)it.classList.add('open');}
  catch(err){alert(err.message);}
}

/* AI-tekstforslag for en oppgave */
async function suggestText(eventId,revise){
  const e=DATA.find(x=>x.id===eventId);
  const btn=document.getElementById(revise?'aiReviseBtn':'aiBtn');const orig=btn?btn.textContent:'';
  if(btn){btn.disabled=true;btn.textContent='✨ Skriver…';}
  try{
    const payload={title:e.title,sport:e.sport,label:val('t_label'),date:val('t_date'),goal:e.mal,extra:e.desc,brief:e.brief||''};
    if(revise){payload.existing=val('t_body');payload.year=String((new Date(e.date)).getFullYear());}
    else{const d=val('t_body');if(d)payload.draft=d;}
    const res=await fetch('/ai/suggest',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify(payload)});
    const j=await res.json();
    if(!res.ok)throw new Error(j.error||('Feil '+res.status));
    document.getElementById('t_body').value=j.text||'';
  }catch(err){alert('Kunne ikke lage tekst: '+err.message);}
  if(btn){btn.disabled=false;btn.textContent=orig;}
}

/* kopier oppgavetekst til utklippstavle */
function copyText(btn,eventId,taskId){
  const e=DATA.find(x=>x.id===eventId);const p=(e.posts||[]).find(x=>x.id===taskId);if(!p)return;
  const t=p.body||'';
  const done=()=>{const o=btn.textContent;btn.textContent='✓ Kopiert!';setTimeout(()=>{btn.textContent=o;},1500);};
  if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(t).then(done).catch(()=>fallbackCopy(t,done));}
  else fallbackCopy(t,done);
}
function fallbackCopy(t,done){const ta=document.createElement('textarea');ta.value=t;document.body.appendChild(ta);ta.select();try{document.execCommand('copy');}catch(e){}document.body.removeChild(ta);if(done)done();}

renderStats();renderUrgent();renderUpcoming();populateFilters();
</script>
@endverbatim
</body>
</html>
