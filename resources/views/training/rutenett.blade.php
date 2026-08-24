<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<title>Treningstider · Rutenett</title>
<style>
  :root{--bg:#f3f5fb;--card:#fff;--ink:#1a1f33;--ink-soft:#5b6b86;--line:#e6eaf2;--flik:#2f6fd6;--grey:#8795a3;--accent:#fb471f;--spind:#fb471f;--bobcats:#1a9aa0}
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:var(--ink);font-family:'Ubuntu',system-ui,sans-serif;font-size:14px;line-height:1.5}
  .wrap{max-width:1200px;margin:0 auto;padding:24px 20px 70px}
  a{color:var(--flik);text-decoration:none}a:hover{text-decoration:underline}
  h1{font-size:23px;font-weight:700;margin:6px 0 2px}
  .sub{color:var(--ink-soft);font-size:13px;margin:0 0 16px;max-width:80ch}
  .subnav{display:flex;gap:6px;margin:14px 0 18px;flex-wrap:wrap}
  .subnav a{padding:7px 14px;border-radius:9px;border:1px solid var(--line);background:var(--card);color:var(--ink-soft);font-weight:500}
  .subnav a.active{border-color:var(--flik);color:var(--flik);background:#eef4fd}
  .ftabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px}
  .ftab{padding:8px 13px;border-radius:9px;border:1px solid var(--line);background:#fff;color:var(--ink-soft);font-weight:500;cursor:pointer;font-family:inherit;font-size:13px}
  .ftab.active{border-color:var(--flik);color:#fff;background:var(--flik)}
  .ftab .k{font-size:11px;opacity:.7;margin-left:5px}
  .vbar{display:flex;gap:8px;align-items:center;flex-wrap:wrap;background:#fff;border:1px solid var(--line);border-radius:11px;padding:9px 12px;margin-bottom:14px}
  .vbar .btn.sm{padding:6px 11px;font-size:12.5px}
  .vbar select{max-width:280px;font-family:inherit;font-size:13px;padding:7px 9px;border:1px solid var(--line);border-radius:8px;background:#fbfcfe}
  .vbar .vsep{flex:1}
  .btn.danger{color:#b23535;border-color:#f0d2d2}
  .layout{display:flex;gap:16px;align-items:flex-start}
  .gridwrap{flex:1;min-width:0;overflow-x:auto}
  .palette{width:210px;flex:none;position:sticky;top:14px;background:#fff;border:1px solid var(--line);border-radius:12px;padding:12px;max-height:80vh;overflow:auto}
  .palette h4{margin:0 0 4px;font-size:12px;text-transform:uppercase;letter-spacing:.03em;color:var(--grey)}
  .palette .hint{font-size:11.5px;color:var(--grey);margin:0 0 10px}
  .pg{margin-bottom:12px}.pgh{font-size:11px;font-weight:700;text-transform:uppercase;color:var(--ink-soft);margin:0 0 5px}
  .pchip{display:flex;align-items:center;gap:6px;font-size:12.5px;font-weight:600;color:#fff;border-radius:7px;padding:5px 9px;margin-bottom:5px;cursor:grab;user-select:none}
  .pchip .ct{margin-left:auto;background:rgba(255,255,255,.28);border-radius:5px;padding:0 6px;font-size:11px}
  .sg{display:grid;grid-template-columns:54px repeat(5,minmax(150px,1fr));gap:2px;min-width:820px}
  .sg .hd{font-size:11.5px;text-transform:uppercase;letter-spacing:.03em;color:var(--grey);font-weight:600;padding:0 0 6px 2px;border-bottom:1px solid var(--line);margin-bottom:2px}
  .axis{position:relative}
  .axis .tl{position:absolute;left:0;right:6px;text-align:right;font-size:11px;color:var(--grey);font-variant-numeric:tabular-nums}
  .lane{position:relative;border-radius:6px;background:linear-gradient(180deg,#fbfcfe,#fbfcfe);border:1px solid var(--line);cursor:copy}
  .lane .row{position:absolute;left:0;right:0;border-top:1px dashed #eef1f6}
  .blk{position:absolute;border-radius:6px;padding:3px 6px;color:#fff;overflow:hidden;cursor:pointer;border-left:3px solid rgba(0,0,0,.18);box-shadow:0 1px 2px rgba(20,40,80,.12)}
  .blk .lab{font-weight:700;font-size:11.5px;line-height:1.15;white-space:nowrap;text-overflow:ellipsis;overflow:hidden}
  .blk .tm{font-size:10px;opacity:.85}
  .blk.locked{background-image:repeating-linear-gradient(135deg,rgba(255,255,255,.22) 0 5px,transparent 5px 10px)}
  .blk.conf{outline:2px solid #ffd21f;outline-offset:1px}
  .blk .lk{position:absolute;top:2px;right:3px;font-size:9px;opacity:.9}
  .legend{font-size:12px;color:var(--ink-soft);margin:12px 0 0;display:flex;flex-wrap:wrap;gap:14px}
  .legend span{display:inline-flex;align-items:center;gap:6px}.sw{width:11px;height:11px;border-radius:3px;display:inline-block}
  .overlay{position:fixed;inset:0;background:rgba(20,30,55,.42);display:none;align-items:flex-start;justify-content:center;padding:44px 16px;z-index:100;overflow:auto}
  .overlay.open{display:flex}
  .modal{background:#fff;border-radius:16px;max-width:440px;width:100%;box-shadow:0 24px 70px rgba(20,40,80,.28)}
  .mhead{padding:15px 20px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
  .mhead h3{margin:0;font-size:15px;font-weight:700}.mhead .x{background:none;border:none;font-size:22px;color:var(--grey);cursor:pointer;line-height:1}
  .mbody{padding:16px 20px}
  .mbody label{display:block;font-size:12.5px;font-weight:600;color:var(--ink-soft);margin:11px 0 4px}
  select,.mbody input[type=text]{width:100%;font-family:inherit;font-size:14px;padding:9px 11px;border:1px solid var(--line);border-radius:9px;background:#fbfcfe;color:var(--ink)}
  .row2{display:flex;gap:8px}.row2>*{flex:1}
  .ck{display:flex;align-items:center;gap:8px;margin-top:12px;font-size:13px;color:var(--ink)}
  .ck input{width:16px;height:16px;accent-color:var(--flik)}
  .mfoot{display:flex;gap:8px;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--line)}
  .btn{font-family:inherit;font-size:13px;font-weight:500;padding:9px 15px;border-radius:9px;border:1px solid var(--line);background:#fff;color:var(--flik);cursor:pointer}
  .btn.solid{background:var(--flik);color:#fff;border-color:var(--flik)}
  .btn.danger{color:#b23535;border-color:#f0d2d2}
</style>
</head>
<body>
@include('partials.topbar', ['active' => 'training'])
@include('partials.topbar-js')
<div class="wrap">
  <h1>Treningstider</h1>
  <p class="sub">Tidsnøyaktig rutenett per anlegg – dette er fasiten Kontroll sjekkes mot. Dra et lag fra paletten inn i en dag, eller klikk i rutenettet for å legge til. Klikk en blokk for å endre tid, lag eller låsing.</p>
  <nav class="subnav">
    <a href="/treningstider">Kontroll</a>
    <a href="/treningstider/lag">Lag</a>
    <a href="/treningstider/anlegg">Anlegg</a>
    <a href="/treningstider/rutenett" class="active">Rutenett</a>
  </nav>
  <div class="vbar">
    <button class="btn sm" style="border-color:#7c5cff;color:#7c5cff;font-weight:600" onclick="openAi()">✨ AI-forslag</button>
    <button class="btn sm solid" onclick="saveVersion()">Lagre versjon</button>
    <button class="btn sm" id="undoBtn" onclick="undo()" disabled>↶ Angre</button>
    <span class="vsep"></span>
    <select id="vsel" title="Lagrede versjoner"></select>
    <button class="btn sm" onclick="restoreVersion()">Gjenopprett</button>
    <button class="btn sm danger" onclick="deleteVersion()">Slett</button>
  </div>
  <div class="ftabs" id="ftabs"></div>
  <div class="layout">
    <div class="gridwrap"><div class="sg" id="sg"></div></div>
    <aside class="palette"><h4>Lag</h4><p class="hint">Dra inn i en dag. Tallet = antall plasseringer.</p><div id="palette"></div></aside>
  </div>
  <div class="legend">
    <span><i class="sw" style="background:var(--flik)"></i>FLIK</span>
    <span><i class="sw" style="background:var(--spind)"></i>Spind (låst)</span>
    <span><i class="sw" style="background:var(--bobcats)"></i>Bobcats (låst)</span>
    <span><i class="sw" style="background:#fff;outline:2px solid #ffd21f"></i>Gul kant = samme lag to steder samtidig</span>
  </div>
</div>

<div class="overlay" id="ov"><div class="modal" id="modal"></div></div>

<script>
  window.TG_FAC=@json($facilities); window.TG_TEAMS=@json($teams);
  window.TG_ASSIGN=@json($assignments); window.TG_VERSIONS=@json($versions); window.TG_CSRF='{{ csrf_token() }}';
</script>
<script>
  var FAC=window.TG_FAC||[], TEAMS=window.TG_TEAMS||[], ASSIGN=window.TG_ASSIGN||[], VERSIONS=window.TG_VERSIONS||[], CSRF=window.TG_CSRF;
  var UNDO=[];
  var DAYS=['Mandag','Tirsdag','Onsdag','Torsdag','Fredag'];
  var TIMES=['16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30'];
  var ENDS=TIMES.concat(['22:00']);
  var SLOTH=34, curFac=FAC.length?FAC[0].id:null, editing=null, dragTeamId=null;
  function esc(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');}
  function mins(t){var p=t.split(':');return +p[0]*60+ +p[1];}
  function facName(id){var f=FAC.find(function(x){return x.id===id;});return f?f.name:'';}
  function is3er(id){return /^3er bane/.test(facName(id));}
  async function api(method,url,body){
    var r=await fetch(url,{method:method,headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},body:body?JSON.stringify(body):undefined});
    if(r.status===422){var j=await r.json();throw new Error(Object.values(j.errors||{}).flat().join('\n'));}
    if(!r.ok)throw new Error('Feil ('+r.status+').');
    return r.status===204?null:r.json();
  }
  var GENERIC=/^(kamper|friidrett|gatefotball|old boys|spind|bobcats|flik)/i;
  function ident(a){return a.team_id?('t'+a.team_id):('l'+String(a.label||'').trim().toLowerCase());}
  function conflicts(){
    var conf={};
    for(var i=0;i<ASSIGN.length;i++)for(var j=i+1;j<ASSIGN.length;j++){
      var a=ASSIGN[i],b=ASSIGN[j];
      if(a.weekday!==b.weekday||a.facility_id===b.facility_id)continue;
      if(!a.team_id&&GENERIC.test(a.label||''))continue;
      if(ident(a)!==ident(b))continue;
      if(!(mins(a.block_start)<mins(b.block_end)&&mins(b.block_start)<mins(a.block_end)))continue;
      if(is3er(a.facility_id)&&is3er(b.facility_id))continue;
      conf[a.id]=1;conf[b.id]=1;
    }
    return conf;
  }
  function clusters(bs){
    bs=bs.slice().sort(function(a,b){return mins(a.block_start)-mins(b.block_start);});
    var res=[],cur=[],end=-1;
    bs.forEach(function(b){
      if(cur.length&&mins(b.block_start)>=end){res.push(cur);cur=[];end=-1;}
      cur.push(b);end=Math.max(end,mins(b.block_end));
    });
    if(cur.length)res.push(cur);
    return res;
  }
  function layout(bs){ // returnerer [{b,lane,nl}]
    var out=[];
    clusters(bs).forEach(function(cl){
      var lanes=[];
      cl.forEach(function(b){
        var li=0;for(;li<lanes.length;li++){if(mins(lanes[li])<=mins(b.block_start))break;}
        lanes[li]=b.block_end;b._lane=li;
      });
      var nl=lanes.length;
      cl.forEach(function(b){out.push({b:b,lane:b._lane,nl:nl});});
    });
    return out;
  }
  function render(){
    document.getElementById('ftabs').innerHTML=FAC.map(function(f){
      return '<button class="ftab'+(f.id===curFac?' active':'')+'" onclick="selFac('+f.id+')">'+esc(f.name)+(f.status==='kommende'?'<span class="k">kommende</span>':'')+'</button>';
    }).join('');
    var conf=conflicts(), H=TIMES.length*SLOTH;
    var html='<div class="hd"></div>'+DAYS.map(function(d){return '<div class="hd">'+d+'</div>';}).join('');
    // tidsakse
    html+='<div class="axis" style="height:'+H+'px">'+TIMES.map(function(t,i){return '<div class="tl" style="top:'+(i*SLOTH-6)+'px">'+t+'</div>';}).join('')+'</div>';
    DAYS.forEach(function(d){
      html+='<div class="lane" data-day="'+d+'" style="height:'+H+'px" onclick="laneClick(event,\''+d+'\')">';
      for(var i=1;i<TIMES.length;i++)html+='<div class="row" style="top:'+(i*SLOTH)+'px"></div>';
      var bs=ASSIGN.filter(function(a){return a.facility_id===curFac&&a.weekday===d;});
      layout(bs).forEach(function(o){
        var b=o.b, top=(mins(b.block_start)-mins('16:00'))/30*SLOTH;
        var h=(mins(b.block_end)-mins(b.block_start))/30*SLOTH-2;
        var w=100/o.nl, left=o.lane*w;
        html+='<div class="blk'+(b.locked?' locked':'')+(conf[b.id]?' conf':'')+'" style="top:'+top+'px;height:'+Math.max(h,16)+'px;left:calc('+left+'% + 1px);width:calc('+w+'% - 2px);background:'+(b.color||'#2f6fd6')+'" onclick="editBlk(event,'+b.id+')">'+
          (b.locked?'<span class="lk">🔒</span>':'')+
          '<div class="lab">'+esc(b.label||'')+'</div>'+
          '<div class="tm">'+b.block_start+'–'+b.block_end+'</div></div>';
      });
      html+='</div>';
    });
    document.getElementById('sg').innerHTML=html;
    renderPalette();
    renderVbar();
  }
  function cardToPayload(c){return {facility_id:c.facility_id,team_id:c.team_id||null,label:c.label||null,org:c.org||'FLIK',locked:!!c.locked,weekday:c.weekday,block_start:c.block_start,block_end:c.block_end};}
  function updateUndoBtn(){var b=document.getElementById('undoBtn');if(b)b.disabled=!UNDO.length;}
  function pushUndo(e){UNDO.push(e);if(UNDO.length>30)UNDO.shift();updateUndoBtn();}
  async function undo(){
    var u=UNDO.pop();if(!u)return;
    try{
      if(u.op==='del'){await api('DELETE','/treningstider/tildeling/'+u.id);ASSIGN=ASSIGN.filter(function(x){return x.id!==u.id;});}
      else if(u.op==='put'){var r=await api('PUT','/treningstider/tildeling/'+u.id,u.payload);var i=ASSIGN.findIndex(function(x){return x.id===u.id;});if(i>-1)ASSIGN[i]=r;}
      else if(u.op==='post'){var a=await api('POST','/treningstider/tildeling',u.payload);ASSIGN.push(a);}
      updateUndoBtn();render();
    }catch(e){alert(e.message);}
  }
  function renderVbar(){
    var sel=document.getElementById('vsel');if(!sel)return;
    sel.innerHTML=VERSIONS.length?VERSIONS.map(function(v){return '<option value="'+v.id+'">'+esc(v.name)+' · '+esc(v.created_at||'')+' ('+v.count+' blokker)'+(v.is_auto?' · auto':'')+'</option>';}).join(''):'<option value="">Ingen lagrede versjoner ennå</option>';
    updateUndoBtn();
  }
  async function saveVersion(){
    var name=prompt('Navn på versjonen:', 'Utkast '+new Date().toLocaleDateString('no-NO'));
    if(!name)return;
    try{var r=await api('POST','/treningstider/versjon',{name:name});VERSIONS=r.versions;renderVbar();alert('Lagret som versjon «'+name+'».');}catch(e){alert(e.message);}
  }
  async function restoreVersion(){
    var id=document.getElementById('vsel').value;if(!id)return;
    if(!confirm('Gjenopprette denne versjonen? Dagens plan lagres automatisk som en versjon først, så du kan gå tilbake.'))return;
    try{var r=await api('POST','/treningstider/versjon/'+id+'/gjenopprett');ASSIGN=r.assignments;VERSIONS=r.versions;UNDO=[];render();}catch(e){alert(e.message);}
  }
  async function deleteVersion(){
    var id=document.getElementById('vsel').value;if(!id)return;
    if(!confirm('Slette denne versjonen?'))return;
    try{var r=await api('DELETE','/treningstider/versjon/'+id);VERSIONS=r.versions;renderVbar();}catch(e){alert(e.message);}
  }
  function openAi(){
    var facOpts=FAC.map(function(f){return '<option value="'+f.id+'">'+esc(f.name)+'</option>';}).join('');
    document.getElementById('modal').innerHTML=
      '<div class="mhead"><h3>✨ AI-forslag</h3><button class="x" onclick="closeModal()">&times;</button></div>'+
      '<div class="mbody">'+
        '<label>Omfang</label>'+
        '<select id="ai_scope" onchange="aiScope()"><option value="alle">Hele planen (mandag–fredag)</option><option value="dag">Én dag</option><option value="anlegg">Ett anlegg</option></select>'+
        '<div id="ai_day_wrap" style="display:none"><label>Dag</label><select id="ai_day">'+opt(DAYS,'Mandag')+'</select></div>'+
        '<div id="ai_fac_wrap" style="display:none"><label>Anlegg</label><select id="ai_fac">'+facOpts+'</select></div>'+
        '<label>Ekstra instruks (valgfritt)</label><input type="text" id="ai_instr" placeholder="F.eks. «la de yngste slutte før 19:00»">'+
        '<p class="hint" style="margin-top:10px">Før AI kjører lagres dagens plan automatisk som versjon «Før AI …», så du kan gå tilbake. Låste tider (Spind/Bobcats) røres ikke.</p>'+
      '</div>'+
      '<div class="mfoot"><div></div><button class="btn solid" id="ai_run" onclick="runAi()">Kjør forslag</button></div>';
    document.getElementById('ov').classList.add('open');
  }
  function aiScope(){var s=document.getElementById('ai_scope').value;document.getElementById('ai_day_wrap').style.display=s==='dag'?'block':'none';document.getElementById('ai_fac_wrap').style.display=s==='anlegg'?'block':'none';}
  async function runAi(){
    var body={scope:document.getElementById('ai_scope').value,day:document.getElementById('ai_day').value,facility_id:+document.getElementById('ai_fac').value,instruction:document.getElementById('ai_instr').value.trim()};
    var btn=document.getElementById('ai_run');btn.disabled=true;btn.textContent='Jobber … (kan ta et halvt minutt)';
    try{
      var r=await api('POST','/treningstider/ai-forslag',body);
      ASSIGN=r.assignments;VERSIONS=r.versions;UNDO=[];closeModal();render();
      alert('AI la inn '+r.placed+' blokker. Dagens plan er nå AI-forslaget – forrige plan ligger som «Før AI …». Kjør Kontroll for å se om det løser seg.');
    }catch(e){btn.disabled=false;btn.textContent='Kjør forslag';alert(e.message);}
  }
  function renderPalette(){
    var host=document.getElementById('palette');if(!host)return;
    var f=FAC.find(function(x){return x.id===curFac;})||{};
    var allowed=(f.allowed_sports||[]).map(function(s){return String(s).toLowerCase();});
    var list=TEAMS.filter(function(t){return !allowed.length||(t.sport&&allowed.indexOf(String(t.sport).toLowerCase())>-1);});
    if(!list.length){host.innerHTML='<p class="hint">Ingen lag på anleggets idretter.</p>';return;}
    var g={};list.forEach(function(t){(g[t.sport||'—']=g[t.sport||'—']||[]).push(t);});
    host.innerHTML=Object.keys(g).sort().map(function(k){
      return '<div class="pg"><div class="pgh">'+esc(k)+'</div>'+g[k].map(function(t){
        var n=ASSIGN.filter(function(a){return a.team_id===t.id;}).length;
        return '<div class="pchip" draggable="true" ondragstart="dragStart(event,'+t.id+')" style="background:'+(t.color||'#8795a3')+'">'+esc(t.name)+(n?'<span class="ct">'+n+'</span>':'')+'</div>';
      }).join('')+'</div>';
    }).join('');
  }
  function selFac(id){curFac=id;render();}
  function dragStart(ev,id){dragTeamId=id;ev.dataTransfer.setData('text/plain',String(id));ev.dataTransfer.effectAllowed='copy';}
  function slotFromY(el,clientY){var r=el.getBoundingClientRect();var i=Math.floor((clientY-r.top)/SLOTH);return Math.max(0,Math.min(TIMES.length-1,i));}
  // drag-drop på lane
  document.addEventListener('dragover',function(e){if(e.target.closest&&e.target.closest('.lane')){e.preventDefault();}});
  document.addEventListener('drop',function(e){
    var lane=e.target.closest&&e.target.closest('.lane');if(!lane)return;e.preventDefault();
    var id=+(e.dataTransfer.getData('text/plain')||dragTeamId);if(!id)return;
    var t=TEAMS.find(function(x){return x.id===id;});var si=slotFromY(lane,e.clientY);
    openModal(null,{facility_id:curFac,weekday:lane.dataset.day,team_id:id,label:t?t.name:'',org:'FLIK',locked:false,block_start:TIMES[si],block_end:ENDS[Math.min(si+3,ENDS.length-1)]});
  });
  function laneClick(ev,day){
    if(ev.target.closest('.blk'))return;
    var lane=ev.currentTarget;var si=slotFromY(lane,ev.clientY);
    openModal(null,{facility_id:curFac,weekday:day,team_id:null,label:'',org:'FLIK',locked:false,block_start:TIMES[si],block_end:ENDS[Math.min(si+3,ENDS.length-1)]});
  }
  function editBlk(ev,id){ev.stopPropagation();var a=ASSIGN.find(function(x){return x.id===id;});if(a)openModal(a,a);}
  function opt(list,sel){return list.map(function(v){return '<option'+(v===sel?' selected':'')+'>'+v+'</option>';}).join('');}
  function openModal(existing,d){
    editing=existing;
    var teamOpts='<option value="">— fri tekst —</option>'+TEAMS.map(function(t){return '<option value="'+t.id+'"'+(d.team_id===t.id?' selected':'')+'>'+esc(t.name)+(t.sport?' ('+esc(t.sport)+')':'')+'</option>';}).join('');
    document.getElementById('modal').innerHTML=
      '<div class="mhead"><h3>'+(existing?'Endre blokk':'Ny blokk')+' · '+esc(facName(d.facility_id))+'</h3><button class="x" onclick="closeModal()">&times;</button></div>'+
      '<div class="mbody">'+
        '<label>Lag</label><select id="m_team" onchange="onTeam()">'+teamOpts+'</select>'+
        '<label>Etikett (vises i ruta)</label><input type="text" id="m_label" value="'+esc(d.label||'')+'" placeholder="F.eks. G14, Kamper, Friidrett">'+
        '<div class="row2"><div><label>Dag</label><select id="m_day">'+opt(DAYS,d.weekday)+'</select></div>'+
        '<div><label>Eier</label><select id="m_org" onchange="onOrg()">'+opt(['FLIK','Spind','Bobcats'],d.org||'FLIK')+'</select></div></div>'+
        '<div class="row2"><div><label>Fra</label><select id="m_start">'+opt(TIMES,d.block_start)+'</select></div>'+
        '<div><label>Til</label><select id="m_end">'+opt(ENDS,d.block_end)+'</select></div></div>'+
        '<label class="ck"><input type="checkbox" id="m_lock"'+(d.locked?' checked':'')+'> Låst (annen klubb – kan ikke røres av FLIK)</label>'+
      '</div>'+
      '<div class="mfoot"><div>'+(existing?'<button class="btn danger" onclick="delBlk()">Slett</button>':'')+'</div>'+
        '<button class="btn solid" onclick="saveBlk()">Lagre</button></div>';
    document.getElementById('ov').classList.add('open');
  }
  function onTeam(){var s=document.getElementById('m_team');var t=TEAMS.find(function(x){return x.id===+s.value;});if(t)document.getElementById('m_label').value=t.name;}
  function onOrg(){document.getElementById('m_lock').checked=document.getElementById('m_org').value!=='FLIK';}
  function closeModal(){document.getElementById('ov').classList.remove('open');editing=null;}
  function payload(){
    var tid=document.getElementById('m_team').value;
    return {facility_id:curFac,team_id:tid?+tid:null,label:document.getElementById('m_label').value.trim()||null,
      org:document.getElementById('m_org').value,locked:document.getElementById('m_lock').checked,
      weekday:document.getElementById('m_day').value,block_start:document.getElementById('m_start').value,block_end:document.getElementById('m_end').value};
  }
  async function saveBlk(){
    var p=payload();
    if(mins(p.block_end)<=mins(p.block_start)){alert('«Til» må være etter «Fra».');return;}
    try{
      if(editing){var before=cardToPayload(editing);var u=await api('PUT','/treningstider/tildeling/'+editing.id,p);var i=ASSIGN.findIndex(function(x){return x.id===editing.id;});ASSIGN[i]=u;pushUndo({op:'put',id:editing.id,payload:before});}
      else{var a=await api('POST','/treningstider/tildeling',p);ASSIGN.push(a);pushUndo({op:'del',id:a.id});}
      closeModal();render();
    }catch(e){alert(e.message);}
  }
  async function delBlk(){
    if(!editing)return;
    try{var restore=cardToPayload(editing);await api('DELETE','/treningstider/tildeling/'+editing.id);ASSIGN=ASSIGN.filter(function(x){return x.id!==editing.id;});pushUndo({op:'post',payload:restore});closeModal();render();}catch(e){alert(e.message);}
  }
  render();
</script>
</body>
</html>
