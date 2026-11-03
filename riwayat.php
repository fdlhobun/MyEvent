<?php if (!empty($rw)): ?>
    <h2>Riwayat transaksi</h2>
    <div class="table-wrap">
        <table>
            <tr>
                <th>No. transaksi</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
            <?php foreach ($rw as $t):
                $ok = $t['status'] === 'berhasil'; ?>
                <tr>
                    <td><?= e($t['no_transaksi']) ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($t['tanggal_transaksi'])) ?></td>
                    <td><?= rp($t['nominal']) ?></td>
                    <td><span class="stat <?= $ok ? 'lunas' : 'gagal' ?>"><?= $ok ? 'Berhasil' : 'Gagal' ?></span></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>