
document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('[data-confirm]').forEach(btn=>btn.addEventListener('click',e=>{if(!confirm(btn.dataset.confirm||'Confirmer ?')) e.preventDefault()}));
  const slugInput=document.querySelector('[data-slug-source]');
  const slugTarget=document.querySelector('[data-slug-target]');
  if(slugInput&&slugTarget&&!slugTarget.value){slugInput.addEventListener('input',()=>{slugTarget.value=slugInput.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')})}
});
