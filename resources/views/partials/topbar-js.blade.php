<script>
  /* Driver den delte headeren på frittstående sider (Treningstider):
     fanene navigerer til appen, meny/innstillinger åpnes der. */
  document.querySelectorAll('.vptop-tabs .tab').forEach(function(t){
    if(t.tagName==='A') return; // Treningstider-lenka navigerer selv
    t.addEventListener('click',function(){ location.href = '/dashboard#' + t.dataset.view; });
  });
  function toggleUserMenu(e){
    e.stopPropagation();
    var dd=document.getElementById('userDD'); if(!dd) return;
    if(dd.classList.contains('open')){ dd.classList.remove('open'); return; }
    dd.innerHTML='<a class="ddi" href="/dashboard#profile">Personlige innstillinger</a>'
      +'<div class="ddsep2"></div>'
      +'<button class="ddi" type="button" onclick="document.getElementById(\'logoutForm\').submit()">Logg ut</button>';
    dd.classList.add('open');
  }
  function openSettings(){ location.href='/dashboard#settings'; }
  document.addEventListener('click',function(){ var dd=document.getElementById('userDD'); if(dd) dd.classList.remove('open'); });
</script>
