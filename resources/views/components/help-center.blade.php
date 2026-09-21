@auth
<style>
    .anicare-help-fab{position:fixed;right:22px;bottom:22px;z-index:1080;width:58px;height:58px;border-radius:50%;border:0;box-shadow:0 8px 24px rgba(0,0,0,.18)}
    .anicare-help-panel{position:fixed;right:22px;bottom:92px;z-index:1079;width:min(370px,calc(100vw - 30px));display:none;border:0;border-radius:20px;overflow:hidden;box-shadow:0 16px 40px rgba(0,0,0,.2)}
    .anicare-help-panel.open{display:block}
</style>
<button type="button" class="anicare-help-fab btn btn-success" id="anicareHelpFab" aria-label="Open ANI-CARE Help"><i class="bi bi-question-lg fs-4"></i></button>
<div class="card anicare-help-panel" id="anicareHelpPanel">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-3">
        <div><strong><i class="bi bi-robot"></i> ANI-CARE Assistant</strong><div class="small opacity-75">{{ ucfirst(auth()->user()->role) }} help</div></div>
        <button type="button" class="btn btn-sm btn-light" id="anicareHelpClose"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="card-body">
        <div class="small text-muted mb-2">Ask how to operate the current module or any ANI-CARE feature.</div>
        <div id="anicareHelpMessages" class="mb-3" style="max-height:300px;overflow:auto"></div>
        <form id="anicareHelpForm">
            @csrf
            <div class="input-group">
                <input id="anicareHelpInput" class="form-control" autocomplete="off" placeholder="Ask a question...">
                <button class="btn btn-success"><i class="bi bi-send"></i></button>
            </div>
        </form>
        <a href="{{ route('help.index') }}" class="btn btn-link btn-sm px-0 mt-2">Open full Help Center</a>
    </div>
</div>
<script>
(function(){
    const fab=document.getElementById('anicareHelpFab'), panel=document.getElementById('anicareHelpPanel'), close=document.getElementById('anicareHelpClose'), form=document.getElementById('anicareHelpForm'), input=document.getElementById('anicareHelpInput'), messages=document.getElementById('anicareHelpMessages');
    if(!fab) return;
    fab.onclick=()=>{panel.classList.add('open');input.focus();};
    close.onclick=()=>panel.classList.remove('open');
    form.onsubmit=async e=>{
        e.preventDefault(); const message=input.value.trim(); if(!message)return;
        messages.insertAdjacentHTML('beforeend','<div class="text-end mb-2"><span class="badge text-bg-success text-wrap">'+esc(message)+'</span></div>'); input.value='';
        messages.insertAdjacentHTML('beforeend','<div class="small text-muted mb-2" id="anicareTyping">Searching Help Center...</div>'); messages.scrollTop=messages.scrollHeight;
        try{
            const r=await fetch('{{ route('help.chat') }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({message,current_route:@json(request()->route()?->getName())})});
            const d=await r.json(); document.getElementById('anicareTyping')?.remove();
            let html='<div class="bg-light rounded-3 p-2 small" style="white-space:pre-line">'+esc(d.answer||'No answer found.')+'</div>';
            if(d.article?.url) html+='<a class="btn btn-sm btn-outline-success mt-2" href="'+d.article.url+'">Open guide</a>';
            messages.insertAdjacentHTML('beforeend','<div class="mb-3">'+html+'</div>'); messages.scrollTop=messages.scrollHeight;
        }catch(err){document.getElementById('anicareTyping')?.remove();messages.insertAdjacentHTML('beforeend','<div class="alert alert-warning small">Help Center is temporarily unavailable. Please open the full Help Center instead.</div>');}
    };
    function esc(v){return String(v).replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));}
})();
</script>
@endauth
