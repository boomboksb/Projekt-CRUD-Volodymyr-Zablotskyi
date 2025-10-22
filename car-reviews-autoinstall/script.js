const API_BASE = 'api';

const $ = (s)=>document.querySelector(s);
const listEl = $('#list');
const form = $('#reviewForm');
const ratingInput = $('#rating');

const stars = Array.from(document.querySelectorAll('.star'));
function paintStars(n){ stars.forEach((el,i)=>{ el.textContent = i < n ? '★' : '☆'; }); }
stars.forEach(s=> s.addEventListener('click',()=>{ const v = Number(s.dataset.v); ratingInput.value = v; paintStars(v); }));
paintStars(Number(ratingInput.value||5));

async function apiGet(path){
  const res = await fetch(`${API_BASE}/${path}`, { headers:{'Accept':'application/json'} });
  return res.json();
}
async function apiPost(path, payload){
  const res = await fetch(`${API_BASE}/${path}`, {
    method:'POST',
    headers:{'Content-Type':'application/json','Accept':'application/json'},
    body: JSON.stringify(payload)
  });
  return res.json();
}

async function fetchReviews(){
  try{
    const json = await apiGet('reviews-list.php');
    if(!json.ok) throw new Error('API error');
    return json.data || [];
  }catch(e){
    console.error(e);
    listEl.innerHTML = `<div class="empty">Błąd pobierania danych. Spróbuj odświeżyć stronę.</div>`;
    return [];
  }
}

function renderList(rows){
  listEl.innerHTML = '';
  if(!rows.length){
    const div = document.createElement('div');
    div.className='empty';
    div.textContent='Na razie brak żadnej opinii.';
    listEl.appendChild(div);
    return;
  }

  const usageMap = { owned:'Własny', rented:'Wynajem', testdrive:'Jazda próbna', other:'Inne' };
  const fuelMap  = { petrol:'Benzyna', diesel:'Diesel', hybrid:'Hybryda', electric:'Elektryczny' };

  rows.forEach((x)=>{
    const it = document.createElement('div');
    it.className='item';

    const h = document.createElement('h3');
    h.textContent = `${x.make} ${x.model} • ${x.year}`;

    const meta = document.createElement('div');
    meta.className='meta';
    const d = x.date ?? new Date(x.created_at).toLocaleDateString('pl-PL');
    meta.innerHTML =
      `<span class="tag">${usageMap[x.usage]||'—'}</span>
       <span class="tag">${fuelMap[x.fuel]||x.fuel}</span>
       <span class="tag">Ocena: ${'★'.repeat(x.rating)}${'☆'.repeat(5-x.rating)}</span>
       <span style="margin-left:6px">${d}${x.author? ' • '+x.author:''}</span>`;

    const p = document.createElement('p');
    p.textContent = x.comment || '';

    const btns = document.createElement('div');
    btns.className='btns';

    const del = document.createElement('button');
    del.className='btn-ghost';
    del.type='button';
    del.textContent='Usuń';
    del.addEventListener('click', async ()=>{
      if(!confirm('Usunąć tę opinię z bazy?')) return;
      const resp = await apiPost('reviews-delete.php', { id: x.id });
      if(!resp.ok){ alert('Nie udało się usunąć.'); return; }
      await refresh();
    });

    btns.appendChild(del);
    it.appendChild(h); it.appendChild(meta); it.appendChild(p); it.appendChild(btns);
    listEl.appendChild(it);
  });
}

async function refresh(){
  const rows = await fetchReviews();
  renderList(rows);
}

form.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const fd = new FormData(form);
  const obj = Object.fromEntries(fd.entries());
  obj.rating = Number(obj.rating||5);

  if(!obj.make || !obj.model || !obj.year){
    alert('Proszę uzupełnić Markę, Model i Rok.');
    return;
  }
  obj.year = Number(obj.year);
  obj.date = obj.date || null;

  const resp = await apiPost('reviews-create.php', obj);
  if(!resp.ok){
    alert('Nie udało się zapisać opinii.');
    return;
  }

  form.reset();
  const today = new Date().toISOString().slice(0,10);
  form.querySelector('input[name="date"]').value = today;
  ratingInput.value = 5; paintStars(5);
  await refresh();
});

form.querySelector('input[name="year"]').value = new Date().getFullYear();
form.querySelector('input[name="date"]').value = new Date().toISOString().slice(0,10);
refresh();
