// Memuat daftar anggota dari data/anggota.json
async function muatDaftarAnggota() {
  const tbody = document.querySelector("#dataTable tbody");
  const loading = document.getElementById("loading-indicator");
  if (!tbody || !loading) return;
  loading.hidden = false;
  try {
    await new Promise(resolve => setTimeout(resolve, 350));
    const response = await fetch("../data/anggota.json");
    if (!response.ok) throw new Error(`Gagal mengambil data (status ${response.status})`);
    const members = await response.json();
    tbody.replaceChildren();
    members.forEach(member => {
      const tr = document.createElement("tr");
      [member.no_anggota, member.nama, member.alamat, member.no_hp].forEach(value => {
        const td = document.createElement("td"); td.textContent = value; tr.appendChild(td);
      });
      const action = document.createElement("td");
      action.innerHTML = '<div class="action-buttons"><button type="button" class="btn btn-edit">Edit</button><button type="button" class="btn btn-delete btn-hapus">Hapus</button></div>';
      tr.appendChild(action); tbody.appendChild(tr);
    });
    if (members.length === 0) tbody.innerHTML = '<tr><td colspan="5" class="empty-message">Belum ada data anggota.</td></tr>';
    document.dispatchEvent(new Event("dataLoaded"));
  } catch (error) {
    tbody.innerHTML = `<tr><td colspan="5" class="empty-message text-danger">Gagal memuat data: ${error.message}. Jalankan melalui Live Server.</td></tr>`;
  } finally { loading.hidden = true; }
}
document.addEventListener("DOMContentLoaded", muatDaftarAnggota);
