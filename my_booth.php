<?php
require 'classes.php';
$judul = 'My Booth';
$nav = 'my';
$pm = new Pemesanan;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batal'])) {
    try {
        $pm->hapus((int) $_POST['batal']);
        flash('Pesanan dibatalkan, booth kembali tersedia.');
    } catch (Exception $x) {
        flash($x->getMessage());
    }
    go('my_booth.php');
}
$list = $pm->semua();
include 'header.php'; ?>
<h1>My Booth</h1>
<p class="loc">Booth yang kamu sewa tersimpan di sini.</p>
<?php if (!$list): ?>
      <p class="empty">Belum ada booth. Pilih event di <a href="index.php">Home</a> lalu klik booth hijau.</p><?php endif; ?>
<div class="table-wrap"><?php if ($list): ?>
        <table>
          <tr>
            <th>Booth</th>
            <th>Event</th>
            <th>Penyewa</th>
            <th>Komunitas</th>
            <th>Tanggal sewa</th>
            <th>Metode</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
          <?php foreach ($list as $r):
              $lunas = $r['status_bayar'] === 'lunas'; ?>
                <tr>
                  <td><b><?= e($r['kode_booth']) ?></b></td>
                  <td><?= e($r['nama_event']) ?></td>
                  <td><?= e($r['nama_penyewa']) ?></td>
                  <td><?= e($r['asal_komunitas']) ?></td>
                  <td><?= tgl($r['tanggal_sewa']) ?></td>
                  <td><?= e($r['metode']) ?></td>
                  <td><?= rp($r['jumlah_bayar']) ?></td>
                  <td>
                    <?= $lunas ? '<a class="stat lunas" href="bukti.php?id=' . $r['id_pemesanan'] . '">Lunas, lihat bukti</a>' : '<a class="stat belum" href="bayar.php?id=' . $r['id_pemesanan'] . '">Belum lunas, bayar</a>' ?>
                  </td>
                  <td class="aksi"><?php if (!$lunas): ?><a href="edit.php?id=<?= $r['id_pemesanan'] ?>">Ubah</a>
                          <form method="post" onsubmit="return confirm('Batalkan pesanan booth <?= e($r['kode_booth']) ?>?')"><button
                              name="batal" value="<?= $r['id_pemesanan'] ?>" class="link danger">Batalkan</button></form>
                    <?php else: ?>-<?php endif; ?>
                  </td>
                </tr>
          <?php endforeach; ?>
        </table><?php endif; ?>
</div>
<?php include 'footer.php'; ?>