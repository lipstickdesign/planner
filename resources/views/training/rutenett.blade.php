<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<title>Treningstider · Rutenett</title>
<style>
  :root{--bg:#f3f5fb;--card:#fff;--ink:#1a1f33;--ink-soft:#5b6b86;--line:#e6eaf2;--flik:#2f6fd6;--grey:#8795a3;--accent:#fb471f;--radius:16px}
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:var(--ink);font-family:'Ubuntu',system-ui,sans-serif;font-size:14px;line-height:1.5}
  .wrap{max-width:1120px;margin:0 auto;padding:24px 20px 70px}
  a{color:var(--flik);text-decoration:none}a:hover{text-decoration:underline}
  h1{font-size:23px;font-weight:700;margin:6px 0 2px}
  .sub{color:var(--ink-soft);font-size:13px;margin:0 0 16px}
  .subnav{display:flex;gap:6px;margin:14px 0 18px;flex-wrap:wrap}
  .subnav a{padding:7px 14px;border-radius:9px;border:1px solid var(--line);background:var(--card);color:var(--ink-soft);font-weight:500}
  .subnav a.active{border-color:var(--flik);color:var(--flik);background:#eef4fd}
  .ftabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px}
  .ftab{padding:8px 13px;border-radius:9px;border:1px solid var(--line);background:#fff;color:var(--ink-soft);font-weight:500;cursor:pointer;font-family:inherit;font-size:13px}
  .ftab.active{border-color:var(--flik);color:#fff;background:var(--flik)}
  .ftab .k{font-size:11px;opacity:.7;margin-left:5px}
  .grid{width:100%;border-collapse:separate;border-spacing:4px;table-layout:fixed}
  .grid th{font-size:12px;text-transform:uppercase;letter-spacing:.03em;color:var(--grey);font-weight:600;padding:4px}
  .grid td.tl{font-size:11.5px;color:var(--grey);font-variant-numeric:tabular-nums;width:64px;text-align:right;padding-right:8px;vertical-align:top}
  .cell{background:#fff;border:1px solid var(--line);border-radius:9px;min-height:66px;padding:6px;cursor:pointer;vertical-align:top}
  .cell:hover{border-color:var(--flik)}
  .cell.locked{background:repeating-linear-gradient(135deg,#f3f4f7 0 6px,#e9ebf1 6px 8px);border-color:#d7dbe4}
  .cell .lockowner{font-size:12px;font-weight:700;color:var(--ink-soft)}
  .chip{display:inline-block;font-size:11.5px;font-weight:600;color:#fff;border-radius:6px;padding:2px 7px;margin:2px 3px 0 0}
  .chip.conf{outline:2px solid var(--accent);outline-offset:1px}
  .cell .empty{color:#c4ccd8;font-size:12px}
  .legend{font-size:12px;color:var(--ink-soft);margin:10px 0 0}
  .overlay{position:fixed;inset:0;background:rgba(20,30,55,.42);display:none;align-items:flex-start;justify-content:center;padding:50px 16px;z-index:100;overflow:auto}
  .overlay.open{display:flex}
  .modal{background:#fff;border-radius:16px;max-width:460px;width:100%;box-shadow:0 24px 70px rgba(20,40,80,.28)}
  .mhead{padding:16px 20px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
  .mhead h3{margin:0;font-size:15px;font-weight:700}
  .mhead .x{background:none;border:none;font-size:22px;color:var(--grey);cursor:pointer;line-height:1}
  .mbody{padding:16px 20px}
  .mbody label{display:block;font-size:12.5px;font-weight:600;color:var(--ink-soft);margin:12px 0 4px}
  select,.mbody input{width:100%;font-family:inherit;font-size:14px;padding:9px 11px;border:1px solid var(--line);border-radius:9px;background:#fbfcfe;color:var(--ink)}
  .row{display:flex;gap:8px}
  .row select{flex:1}
  .btn{font-family:inherit;font-size:13px;font-weight:500;padding:9px 14px;border-radius:9px;border:1px solid var(--line);background:#fff;color:var(--flik);cursor:pointer}
  .btn.solid{background:var(--flik);color:#fff;border-color:var(--flik)}
  .btn.sm{padding:7px 11px;font-size:12.5px}
  .assigned{display:flex;flex-wrap:wrap;gap:6px;margin-top:4px}
  .assigned .a{display:flex;align-items:center;gap:6px;background:#f5f7fb;border:1px solid var(--line);border-radius:8px;padding:4px 6px 4px 9px;font-size:12.5px}
  .assigned .a b{width:9px;height:9px;border-radius:3px;display:inline-block}
  .assigned .a button{background:none;border:none;color:#b23535;cursor:pointer;font-size:15px;line-height:1}
  .muted{color:var(--grey)}
</style>
</head>
<body>
@include('partials.topbar', ['active' => 'training'])
@include('partials.topbar-js')
<div class="wrap">
  <h1>Treningstider</h1>
  <p class="sub">Rutenett per anlegg. Klikk en rute for å legge til lag eller markere den som låst (Spind/Bobcats).</p>
  <nav class="subnav">
    <a href="/treningstider">Kontroll</a>
    <a href="/treningstider/lag">Lag</a>
    <a href="/treningstider/anlegg">Anlegg</a>
    <a href="/treningstider/rutenett" class="active">Rutenett</a>
  </nav>
  <div class="ftabs" id="ftabs"></div>
  <div id="gridHost"></div>
  <p class="legend">Skravert = låst hos annen klubb. Oransje kant på et lag = samme lag er satt opp et annet sted samtidig.</p>
</div>

<div class="overlay" id="gOverlay"><div class="modal" id="gModal"></div></div>

<script>
  window.TG_FAC=@json($facilities); window.TG_TEAMS=@json($teams);
  window.TG_ASSIGN=@json($assignments); window.TG_LOCKS=@json($locks); window.TG_CSRF='{{ csrf_token() }}';
</script>
<script>
  var FAC=window.TG_FAC||[], TEAMS=window.TG_TEAMS||[], ASSIGN=window.TG_ASSIGN||[], LOCKS=window.TG_LOCKS||[], CSRF=window.TG_CSRF;
  var DAYS=['Mandag','Tirsdag','Onsdag','Torsdag','Fredag'];
  var BANDS=[['16:00','17:30'],['17:30','19:00'],['19:00','20:30'],['20:30','22:00']];
  var curFac=FAC.length?FAC[0].id:null, cur=null;
  function esc(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');}
  async function api(method,url,body){
    var r=await fetch(url,{method:method,headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},body:body?JSON.stringify(body):undefined});
    if(r.status===422){var j=await r.json();throw new Error(Object.values(j.errors||{}).flat().join('\n')||'Ugyldige data');}
    if(!r.ok)throw new Error('Noe gikk galt ('+r.status+').');
    return r.status===204?null:r.json();
  }
  function conflicts(){
    // lag som er på to anlegg samme dag+bånd
    var map={}, conf={};
    ASSIGN.forEach(function(a){var k=a.team_id+'|'+a.weekday+'|'+a.block_start;(map[k]=map[k]||[]).push(a);});
    Object.keys(map).forEach(function(k){var arr=map[k];var fac={};arr.forEach(function(a){fac[a.facility_id]=1;});if(Object.keys(fac).length>1)arr.forEach(function(a){conf[a.id]=1;});});
    return conf;
  }
  function render(){
    document.getElementById('ftabs').innerHTML=FAC.map(function(f){
      return '<button class="ftab'+(f.id===curFac?' active':'')+'" onclick="selFac('+f.id+')">'+esc(f.name)+(f.status==='kommende'?'<span class="k">kommende</span>':'')+'</button>';
    }).join('');
    var conf=conflicts();
    var html='<table class="grid"><thead><tr><th></th>'+DAYS.map(function(d){return '<th>'+d+'</th>';}).join('')+'</tr></thead><tbody>';
    BANDS.forEach(function(b){
      html+='<tr><td class="tl">'+b[0]+'<br>–<br>'+b[1]+'</td>';
      DAYS.forEach(function(d){
        var lock=LOCKS.find(function(l){return l.facility_id===curFac&&l.weekday===d&&l.block_start===b[0];});
        var as=ASSIGN.filter(function(a){return a.facility_id===curFac&&a.weekday===d&&a.block_start===b[0];});
        var inner;
        if(lock){inner='<div class="lockowner">🔒 '+esc(lock.owner)+'</div>';}
        else if(as.length){inner=as.map(function(a){return '<span class="chip'+(conf[a.id]?' conf':'')+'" style="background:'+(a.color||'#8795a3')+'">'+esc(a.team_name)+'</span>';}).join('');}
        else{inner='<span class="empty">+</span>';}
        html+='<td class="cell'+(lock?' locked':'')+'" onclick="openCell(\''+d+'\','+BANDS.indexOf(b)+')">'+inner+'</td>';
      });
      html+='</tr>';
    });
    html+='</tbody></table>';
    document.getElementById('gridHost').innerHTML=html;
  }
  function selFac(id){curFac=id;render();}
  function closeModal(){document.getElementById('gOverlay').classList.remove('open');}
  function openCell(day,bi){
    var b=BANDS[bi];cur={day:day,bi:bi,start:b[0],end:b[1]};
    var lock=LOCKS.find(function(l){return l.facility_id===curFac&&l.weekday===day&&l.block_start===b[0];});
    var as=ASSIGN.filter(function(a){return a.facility_id===curFac&&a.weekday===day&&a.block_start===b[0];});
    var facName=(FAC.find(function(f){return f.id===curFac;})||{}).name||'';
    var body;
    if(lock){
      body='<p class="muted">Denne ruta er låst hos <b>'+esc(lock.owner)+'</b>.</p>'+
        '<button class="btn solid" onclick="unlock('+lock.id+')">Lås opp</button>';
    }else{
      var teamOpts='<option value="">– velg lag –</option>'+TEAMS.map(function(t){return '<option value="'+t.id+'">'+esc(t.name)+(t.sport?' ('+esc(t.sport)+')':'')+'</option>';}).join('');
      body=(as.length?'<label>Lag i denne ruta</label><div class="assigned">'+as.map(function(a){return '<span class="a"><b style="background:'+(a.color||'#8795a3')+'"></b>'+esc(a.team_name)+'<button onclick="rmTeam('+a.id+')" title="Fjern">&times;</button></span>';}).join('')+'</div>':'')+
        '<label>Legg til lag</label><div class="row"><select id="g_team">'+teamOpts+'</select><button class="btn solid sm" onclick="addTeam()">Legg til</button></div>'+
        '<label>Marker som låst hos annen klubb</label><div class="row"><select id="g_owner"><option>Spind</option><option>Bobcats</option><option>Annet</option></select><button class="btn sm" onclick="lockCell()">Lås</button></div>';
    }
    document.getElementById('gModal').innerHTML=
      '<div class="mhead"><h3>'+esc(facName)+' · '+day+' '+b[0]+'–'+b[1]+'</h3><button class="x" onclick="closeModal()">&times;</button></div>'+
      '<div class="mbody">'+body+'</div>';
    document.getElementById('gOverlay').classList.add('open');
  }
  async function addTeam(){
    var tid=+document.getElementById('g_team').value;if(!tid)return;
    try{var a=await api('POST','/treningstider/tildeling',{facility_id:curFac,team_id:tid,weekday:cur.day,block_start:cur.start,block_end:cur.end});ASSIGN.push(a);render();openCell(cur.day,cur.bi);}catch(e){alert(e.message);}
  }
  async function rmTeam(id){
    try{await api('DELETE','/treningstider/tildeling/'+id);ASSIGN=ASSIGN.filter(function(x){return x.id!==id;});render();openCell(cur.day,cur.bi);}catch(e){alert(e.message);}
  }
  async function lockCell(){
    var owner=document.getElementById('g_owner').value;
    try{var l=await api('POST','/treningstider/las',{facility_id:curFac,weekday:cur.day,block_start:cur.start,block_end:cur.end,owner:owner});LOCKS.push(l);closeModal();render();}catch(e){alert(e.message);}
  }
  async function unlock(id){
    try{await api('DELETE','/treningstider/las/'+id);LOCKS=LOCKS.filter(function(x){return x.id!==id;});closeModal();render();}catch(e){alert(e.message);}
  }
  render();
</script>
</body>
</html>
