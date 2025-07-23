<div class="container-fluid">
    <h2 class="mb-4 mt-2" style="color:#1976d2;font-weight:700;">Yêu cầu liên hệ từ khách hàng</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Nội dung liên hệ</th>
                    <th>Thời gian gửi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $i => $r): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($r['name']) ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($r['email']) ?>"><?= htmlspecialchars($r['email']) ?></a></td>
                            <td><?= htmlspecialchars($r['phone']) ?></td>
                            <td><?= nl2br(htmlspecialchars($r['message'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">Chưa có yêu cầu liên hệ nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 