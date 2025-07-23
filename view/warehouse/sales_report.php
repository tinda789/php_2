<?php include 'view/layout/header_admin.php'; ?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-chart-line"></i> Báo cáo bán hàng tháng này</h2>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng số đơn hàng</h5>
                    <h2><?= isset($orderCount) ? $orderCount : 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm bán chạy nhất</h5>
                    <?php if (!empty($bestSeller)): ?>
                        <h4><?= htmlspecialchars($bestSeller['name']) ?></h4>
                        <p>Số lượng bán: <strong><?= $bestSeller['total_sold'] ?></strong></p>
                    <?php else: ?>
                        <p>Không có dữ liệu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm bán ế nhất</h5>
                    <?php if (!empty($worstSeller)): ?>
                        <h4><?= htmlspecialchars($worstSeller['name']) ?></h4>
                        <p>Số lượng bán: <strong><?= $worstSeller['total_sold'] ?></strong></p>
                    <?php else: ?>
                        <p>Không có dữ liệu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <h4 class="mt-4">Thống kê chi tiết sản phẩm bán ra trong tháng</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng bán</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($salesStats)): ?>
                    <?php foreach ($salesStats as $i => $row): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= $row['total_sold'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">Không có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 