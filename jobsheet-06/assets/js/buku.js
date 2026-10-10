// Memuat daftar buku dari data/buku.json
async function muatDaftarBuku() {
  const tbody = document.querySelector("#dataTable tbody");
  const loading = document.getElementById("loading-indicator");
  if (!tbody || !loading) return;
  loading.hidden = false;
  try {
    await new Promise(resolve => setTimeout(resolve, 350));
    const response = await fetch("../data/buku.json");
    if (!response.ok) throw new Error(`Gagal mengambil data (status ${response.status})`);
    const books = await response.json();
    tbody.replaceChildren();
    books.forEach(book => {
      const tr = document.createElement("tr");
      [book.judul, book.pengarang, book.tahun, book.stok].forEach(value => {
        const td = document.createElement("td"); td.textContent = value; tr.appendChild(td);
      });
      const action = document.createElement("td");
      action.innerHTML = '<div class="action-buttons"><button type="button" class="btn btn-edit">Edit</button><button type="button" class="btn btn-delete btn-hapus">Hapus</button></div>';
      tr.appendChild(action); tbody.appendChild(tr);
    });
    if (books.length === 0) tbody.innerHTML = '<tr><td colspan="5" class="empty-message">Belum ada data buku.</td></tr>';
    document.dispatchEvent(new Event("dataLoaded"));
  } catch (error) {
    tbody.innerHTML = `<tr><td colspan="5" class="empty-message text-danger">Gagal memuat data: ${error.message}. Jalankan melalui Live Server.</td></tr>`;
  } finally { loading.hidden = true; }
}
document.addEventListener("DOMContentLoaded", muatDaftarBuku);
