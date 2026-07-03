document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('[data-confirm]').forEach(btn=>btn.addEventListener('click',e=>{if(!confirm(btn.dataset.confirm||'Confirmer ?')) e.preventDefault()}));
  const slugInput=document.querySelector('[data-slug-source]');
  const slugTarget=document.querySelector('[data-slug-target]');
  if(slugInput&&slugTarget&&!slugTarget.value){slugInput.addEventListener('input',()=>{slugTarget.value=slugInput.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')})}

  const form=document.querySelector('[data-assessment-form]');
  if(!form) return;
  const steps=[...form.querySelectorAll('[data-assessment-step]')];
  const prev=form.querySelector('[data-assessment-prev]');
  const next=form.querySelector('[data-assessment-next]');
  const submit=form.querySelector('[data-assessment-submit]');
  const progress=form.querySelector('[data-assessment-progress]');
  const current=form.querySelector('[data-assessment-current]');
  const summary=form.querySelector('[data-assessment-summary] dl');
  const started=form.querySelector('[data-started-field]');
  const completion=form.querySelector('[data-completion-field]');
  const referrer=form.querySelector('[data-referrer-field]');
  let index=0;

  if(started&&!started.value) started.value=String(Date.now());
  if(referrer&&!referrer.value) referrer.value=document.referrer||'';

  function show(i){
    index=Math.max(0,Math.min(i,steps.length-1));
    steps.forEach((step,idx)=>{step.hidden=idx!==index});
    if(progress) progress.value=index+1;
    if(current) current.textContent=String(index+1);
    if(prev) prev.disabled=index===0;
    if(next) next.hidden=index===steps.length-1;
    if(submit) submit.hidden=index!==steps.length-1;
    if(index===steps.length-1) buildSummary();
    steps[index].querySelector('input,select,textarea,button')?.focus({preventScroll:true});
  }

  function invalidate(el,msg){
    el.classList.add('is-invalid');
    const holder=el.closest('label,fieldset');
    const error=holder?.querySelector('.field-error');
    if(error) error.textContent=msg;
  }
  function clearInvalid(scope){
    scope.querySelectorAll('.is-invalid').forEach(el=>el.classList.remove('is-invalid'));
    scope.querySelectorAll('.field-error').forEach(el=>el.textContent='');
  }
  function validateStep(){
    const step=steps[index];
    clearInvalid(step);
    let ok=true;
    step.querySelectorAll('input[required],select[required],textarea[required]').forEach(el=>{
      if(el.type==='checkbox'&&!el.checked){ok=false;invalidate(el,'Champ obligatoire.');return;}
      if(el.type!=='checkbox'&&!el.value.trim()){ok=false;invalidate(el,'Champ obligatoire.');return;}
      if(el.type==='email'&&el.value&&!el.checkValidity()){ok=false;invalidate(el,'Email invalide.');}
    });
    step.querySelectorAll('[data-required-group]').forEach(group=>{
      if(!group.querySelector('input[type="checkbox"]:checked')){ok=false;group.classList.add('is-invalid');const error=group.querySelector('.field-error');if(error) error.textContent='Sélectionnez au moins une réponse.';}
    });
    if(!ok) step.querySelector('.is-invalid, input:invalid, select:invalid')?.focus({preventScroll:false});
    return ok;
  }
  function buildSummary(){
    if(!summary) return;
    const items=[];
    const fields=['entreprise','fonction','taille_organisation','priorite_principale','temporalite_action','budget'];
    fields.forEach(name=>{
      const field=form.querySelector(`[name="${name}"]`);
      if(!field) return;
      const label=field.closest('label')?.childNodes[0]?.textContent?.trim()||name;
      const value=field.tagName==='SELECT' ? field.selectedOptions[0]?.textContent : field.value;
      if(value) items.push([label.replace('*','').trim(),value]);
    });
    const checked=[...form.querySelectorAll('[name="objectifs_business[]"]:checked')].map(el=>el.closest('label')?.innerText.trim()).filter(Boolean);
    if(checked.length) items.push(['Objectifs',checked.join(', ')]);
    summary.textContent='';
    items.forEach(([k,v])=>{
      const dt=document.createElement('dt');
      const dd=document.createElement('dd');
      dt.textContent=k;
      dd.textContent=v;
      summary.append(dt,dd);
    });
  }
  next?.addEventListener('click',()=>{if(validateStep()) show(index+1)});
  prev?.addEventListener('click',()=>show(index-1));
  form.addEventListener('submit',e=>{
    if(!validateStep()){e.preventDefault();return;}
    if(completion&&started&&started.value){completion.value=String(Math.max(0,Math.round((Date.now()-Number(started.value))/1000)));}
  });
  show(0);
});
