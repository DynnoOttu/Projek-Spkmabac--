 // MODAL diletakkan di sini, setelah </tr>
  echo "
  <div class='modal fade' id='editModal{$row['id_kriteria']}' tabindex='-1' aria-labelledby='editModalLabel{$row['id_kriteria']}' aria-hidden='true'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-header bg-primary text-white'>
          <h5 class='modal-title' id='editModalLabel{$row['id_kriteria']}'>Edit Kriteria</h5>
          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <form action='editkriteria.php' method='POST'>
          <div class='modal-body'>
            <input type='hidden' name='id_kriteria' value='{$row['id_kriteria']}'>
            <div class='mb-3'>
              <label for='nama{$row['id_kriteria']}' class='form-label'>Nama</label>
              <input type='text' class='form-control' id='nama{$row['id_kriteria']}' name='nama' value='{$row['nama']}' required>
            </div>
            <div class='mb-3'>
              <label for='bobot{$row['id_kriteria']}' class='form-label'>Bobot</label>
              <input type='number' class='form-control' id='bobot{$row['id_kriteria']}' name='bobot' value='{$row['bobot']}' step='0.01' required>
            </div>
            <div class='mb-3'>
              <label for='jenis{$row['id_kriteria']}' class='form-label'>Jenis</label>
              <select class='form-select' id='jenis{$row['id_kriteria']}' name='jenis' required>
  <option value='Benefit' " . ($row['jenis'] == 'Benefit' ? 'selected' : '') . ">Benefit</option>
  <option value='Cost' " . ($row['jenis'] == 'Cost' ? 'selected' : '') . ">Cost</option>
</select>
            </div>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
            <button type='submit' class='btn btn-primary'><i class='bi bi-save2'></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>