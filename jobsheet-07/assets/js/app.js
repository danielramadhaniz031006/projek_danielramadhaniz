// SIMPUS-Mini Jobsheet 7 — interaksi antarmuka
function initNavToggle(){
  const btn=document.getElementById("nav-toggle-btn");
  const nav=document.getElementById("mainNav");
  if(!btn||!nav)return;
  btn.addEventListener("click",()=>{
    const open=nav.classList.toggle("nav-open");
    btn.setAttribute("aria-expanded",String(open));
  });
}
function initHapusConfirm(){
  document.addEventListener("click",e=>{
    const btn=e.target.closest(".btn-hapus");
    if(!btn)return;
    const row=btn.closest("tr");
    const label=row?.querySelector("td")?.textContent.trim()||"data ini";
    if(row&&confirm(`Yakin ingin menghapus "${label}" dari tampilan?`)){
      row.remove();
      updateTableCount();
    }
  });
}
function updateTableCount(){
  const table=document.querySelector(".table-responsive table");
  const count=document.querySelector(".table-count");
  if(!table||!count)return;
  const rows=[...table.querySelectorAll("tbody tr")].filter(r=>!r.querySelector("td[colspan]"));
  const visible=rows.filter(r=>r.style.display!=="none").length;
  count.innerHTML=`Menampilkan <strong>${visible}</strong> dari <strong>${rows.length}</strong> data`;
}
function initTableFilter(){
  const input=document.getElementById("search-input");
  const table=document.querySelector(".table-responsive table");
  if(!input||!table)return;
  input.addEventListener("input",()=>{
    const keyword=input.value.toLocaleLowerCase("id").trim();
    table.querySelectorAll("tbody tr").forEach(row=>{
      row.style.display=row.textContent.toLocaleLowerCase("id").includes(keyword)?"":"none";
    });
    updateTableCount();
  });
  updateTableCount();
}
function tampilkanError(input,pesan){
  hapusError(input);
  const span=document.createElement("span");
  span.className="error";
  span.textContent=pesan;
  input.insertAdjacentElement("afterend",span);
}
function hapusError(input){
  const next=input.nextElementSibling;
  if(next&&next.classList.contains("error"))next.remove();
}
function initValidasiForm(){
  const form=document.getElementById("form-tambah");
  if(!form)return;
  form.addEventListener("submit",e=>{
    let valid=true;
    const requiredFields=form.querySelectorAll("[required]");
    requiredFields.forEach(input=>{
      if(!input.value.trim()){tampilkanError(input,"Bagian ini wajib diisi.");valid=false;}
      else hapusError(input);
    });
    const pengarang=form.querySelector("[name='pengarang']");
    if(pengarang&&!pengarang.value.trim()){tampilkanError(pengarang,"Pengarang wajib diisi.");valid=false;}
    const tahun=form.querySelector("[name='tahun']");
    if(tahun&&tahun.value){
      const n=Number(tahun.value);
      if(!Number.isInteger(n)||n<1900||n>2026){tampilkanError(tahun,"Tahun harus di antara 1900-2026.");valid=false;}
      else hapusError(tahun);
    }
    const stok=form.querySelector("[name='stok']");
    if(stok&&stok.value!==""&&Number(stok.value)<0){tampilkanError(stok,"Stok tidak boleh negatif.");valid=false;}
    else if(stok)hapusError(stok);
    const isbn=form.querySelector("[name='isbn']");
    if(isbn&&isbn.value&&!/^[0-9-]+$/.test(isbn.value)){tampilkanError(isbn,"ISBN hanya boleh berisi angka dan tanda hubung (-).");valid=false;}
    else if(isbn)hapusError(isbn);
    const hp=form.querySelector("[name='no_hp']");
    if(hp&&hp.value&&!/^[0-9]+$/.test(hp.value)){tampilkanError(hp,"No. HP hanya boleh berisi angka.");valid=false;}
    else if(hp)hapusError(hp);
    if(!valid)e.preventDefault();
  });
}
function initReloadButton(){
  const btn=document.getElementById("reload-btn");
  if(btn)btn.addEventListener("click",()=>window.location.reload());
}
document.addEventListener("DOMContentLoaded",()=>{
  initNavToggle();initHapusConfirm();initTableFilter();initValidasiForm();initReloadButton();
});
