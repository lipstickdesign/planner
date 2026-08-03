<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Årshjul – {{ $company->name ?? 'Idrettsklubb' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box}
html,body{margin:0;padding:0;background:transparent}
body{font-family:'Ubuntu',system-ui,sans-serif;color:#1a1f33}
.wrap{max-width:660px;margin:0 auto;padding:14px}
h1{font-size:18px;font-weight:700;color:#1c3155;margin:2px 0 10px;text-align:center}
svg.wheel{width:100%;height:auto;display:block}
.legend{display:flex;flex-wrap:wrap;gap:8px 16px;justify-content:center;margin:14px 0 4px}
.legend .it{display:flex;align-items:center;gap:7px;font-size:12.5px;color:#33415a}
.legend .sw{width:12px;height:12px;border-radius:3px;flex:none}
.foot{text-align:center;font-size:11px;color:#9aa6b6;margin-top:8px}
.foot a{color:#9aa6b6;text-decoration:none}
</style>
</head>
<body>
<div class="wrap">
  <h1>Årshjul {{ date('Y') }} · {{ $company->name ?? '' }}</h1>
  <div id="host"></div>
  <div class="legend" id="legend"></div>
  <div class="foot">Laget med <a href="https://planner.vivu.no" target="_blank" rel="nofollow noopener">Vivu Planner</a></div>
</div>
<script>window.WHEEL = @json($events); window.YEAR = {{ (int) date('Y') }};</script>
@verbatim
<script>
(function(){
  var evs=(window.WHEEL||[]).filter(function(e){return e.date;});
  var MS=['jan','feb','mar','apr','mai','jun','jul','aug','sep','okt','nov','des'];
  var esc=function(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/"/g,'&quot;');};
  var fmt=function(d){var x=new Date(d);return x.getDate()+'. '+MS[x.getMonth()];};
  var size=560,cx=280,cy=280,rO=250,rI=120;
  var svg='<svg class="wheel" viewBox="0 0 '+size+' '+size+'" xmlns="http://www.w3.org/2000/svg">';
  for(var m=0;m<12;m++){
    var a0=(m*30-90)*Math.PI/180,a1=((m+1)*30-90)*Math.PI/180;
    var x0=cx+rO*Math.cos(a0),y0=cy+rO*Math.sin(a0),x1=cx+rO*Math.cos(a1),y1=cy+rO*Math.sin(a1);
    var xi0=cx+rI*Math.cos(a0),yi0=cy+rI*Math.sin(a0),xi1=cx+rI*Math.cos(a1),yi1=cy+rI*Math.sin(a1);
    svg+='<path d="M'+xi0+' '+yi0+' L'+x0+' '+y0+' A'+rO+' '+rO+' 0 0 1 '+x1+' '+y1+' L'+xi1+' '+yi1+' A'+rI+' '+rI+' 0 0 0 '+xi0+' '+yi0+' Z" fill="'+(m%2?'#eef3f9':'#f7fafd')+'" stroke="#dde5ee"/>';
    var am=((m+0.5)*30-90)*Math.PI/180;
    svg+='<text x="'+(cx+(rO+18)*Math.cos(am))+'" y="'+(cy+(rO+18)*Math.sin(am))+'" font-size="12" font-weight="500" fill="#5b6b7b" text-anchor="middle" dominant-baseline="middle" font-family="Ubuntu">'+MS[m].toUpperCase()+'</text>';
  }
  svg+='<circle cx="'+cx+'" cy="'+cy+'" r="'+(rI-6)+'" fill="#1c3155"/>';
  svg+='<text x="'+cx+'" y="'+(cy-8)+'" font-size="30" font-weight="700" fill="#fff" text-anchor="middle" font-family="Ubuntu">FLIK</text>';
  svg+='<text x="'+cx+'" y="'+(cy+16)+'" font-size="13" fill="#cfe0f2" text-anchor="middle" font-family="Ubuntu">Årshjul '+(window.YEAR||'')+'</text>';
  var byMonth={};evs.forEach(function(e){var mm=new Date(e.date).getMonth();(byMonth[mm]=byMonth[mm]||[]).push(e);});
  Object.keys(byMonth).forEach(function(m){
    var list=byMonth[m];
    list.forEach(function(e,i){
      var n=list.length;
      var am=((+m+(n===1?0.5:(0.18+0.64*i/Math.max(1,n-1))))*30-90)*Math.PI/180;
      var rr=rI+30+(i%3)*30;
      var px=cx+rr*Math.cos(am),py=cy+rr*Math.sin(am);
      svg+='<circle cx="'+px+'" cy="'+py+'" r="8.5" fill="'+(e.color||'#8795a3')+'" stroke="#fff" stroke-width="2"><title>'+esc(e.title)+' · '+fmt(e.date)+'</title></circle>';
    });
  });
  svg+='</svg>';
  document.getElementById('host').innerHTML=svg;

  var counts={},colors={};
  evs.forEach(function(e){counts[e.sport]=(counts[e.sport]||0)+1;colors[e.sport]=e.color||'#8795a3';});
  var leg=Object.keys(counts).sort().map(function(s){
    return '<div class="it"><span class="sw" style="background:'+colors[s]+'"></span>'+esc(s)+' ('+counts[s]+')</div>';
  }).join('');
  document.getElementById('legend').innerHTML=leg;
})();
</script>
@endverbatim
</body>
</html>
