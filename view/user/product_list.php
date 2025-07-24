<?php /* @var $products, $total, $totalPages, $categories, $page, $search, $category_id */ ?>
<div class="container-fluid py-4" style="padding-left:0;margin-left:0;">
  <div class="row g-0" style="margin-left:0;">
    <!-- Sidebar lọc -->
    <aside class="col-lg-3 col-md-4 mb-4" style="min-width:200px;max-width:320px;padding-left:0;margin-left:0;">
      <div class="position-sticky" style="top:90px;">
        <form method="GET" action="index.php" class="p-2 bg-white border-end border-2" style="min-height:100vh;">
          <input type="hidden" name="controller" value="product">
          <input type="hidden" name="action" value="list">
          <div class="mb-3">
            <label for="search" class="form-label">Tìm kiếm sản phẩm</label>
            <div class="input-group">
              <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                     placeholder="VD: điện thoại, máy ảnh, laptop, vga, ram..." 
                     list="search-suggestions" autocomplete="off">
              <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
            </div>
            <small class="form-text text-muted">
              <i class="fas fa-lightbulb"></i> Gợi ý: Tìm theo tên sản phẩm, thương hiệu, danh mục. Hỗ trợ tìm kiếm thông minh: "điện thoại", "laptop", "máy ảnh", "linh kiện"!
            </small>
            <datalist id="search-suggestions">
              <option value="điện thoại">Điện thoại</option>
              <option value="iphone">iPhone</option>
              <option value="samsung">Samsung</option>
              <option value="máy ảnh">Máy ảnh</option>
              <option value="laptop">Laptop</option>
              <option value="vga">VGA</option>
              <option value="ram">RAM</option>
              <option value="cpu">CPU</option>
              <option value="mainboard">Mainboard</option>
              <option value="man hinh">Màn hình</option>
              <option value="ban phim">Bàn phím</option>
              <option value="chuot">Chuột</option>
              <option value="tai nghe">Tai nghe</option>
              <option value="loa">Loa</option>
              <option value="usb">USB</option>
              <option value="ssd">SSD</option>
              <option value="hdd">HDD</option>
              <option value="case">Case</option>
              <option value="psu">PSU</option>
              <option value="fan">Fan</option>
              <option value="smartphone">Smartphone</option>
              <option value="tablet">Tablet</option>
              <option value="smartwatch">Smartwatch</option>
              <option value="drone">Drone</option>
              <option value="gaming">Gaming</option>
              <option value="office">Office</option>
              <option value="design">Design</option>
              <option value="video">Video</option>
              <option value="streaming">Streaming</option>
              <option value="wifi">WiFi</option>
              <option value="bluetooth">Bluetooth</option>
              <option value="type-c">Type-C</option>
              <option value="hdmi">HDMI</option>
              <option value="ethernet">Ethernet</option>
              <option value="webcam">Webcam</option>
              <option value="microphone">Microphone</option>
              <option value="printer">Printer</option>
              <option value="scanner">Scanner</option>
              <option value="projector">Projector</option>
              <option value="router">Router</option>
              <option value="server">Server</option>
              <option value="nas">NAS</option>
              <option value="ups">UPS</option>
              <option value="cable">Cable</option>
              <option value="adapter">Adapter</option>
              <option value="charger">Charger</option>
              <option value="battery">Battery</option>
              <option value="led">LED</option>
              <option value="smart home">Smart Home</option>
              <option value="security">Security</option>
              <option value="camera">Camera</option>
              <option value="canon">Canon</option>
              <option value="sony">Sony</option>
              <option value="nikon">Nikon</option>
              <option value="apple">Apple</option>
              <option value="dell">Dell</option>
              <option value="hp">HP</option>
              <option value="asus">ASUS</option>
              <option value="msi">MSI</option>
              <option value="gigabyte">GIGABYTE</option>
              <option value="corsair">Corsair</option>
              <option value="intel">Intel</option>
              <option value="amd">AMD</option>
              <option value="nvidia">NVIDIA</option>
              <option value="rtx">RTX</option>
              <option value="gtx">GTX</option>
              <option value="macbook">MacBook</option>
              <option value="galaxy">Galaxy</option>
              <option value="airpods">AirPods</option>
              <option value="apple watch">Apple Watch</option>
              <option value="roomba">Roomba</option>
              <option value="air purifier">Air Purifier</option>
              <option value="refrigerator">Refrigerator</option>
              <option value="washing machine">Washing Machine</option>
              <option value="dishwasher">Dishwasher</option>
              <option value="oven">Oven</option>
              <option value="microwave">Microwave</option>
              <option value="coffee maker">Coffee Maker</option>
              <option value="blender">Blender</option>
              <option value="juicer">Juicer</option>
              <option value="toaster">Toaster</option>
              <option value="rice cooker">Rice Cooker</option>
              <option value="pressure cooker">Pressure Cooker</option>
              <option value="slow cooker">Slow Cooker</option>
              <option value="air fryer">Air Fryer</option>
              <option value="grill">Grill</option>
              <option value="stove">Stove</option>
              <option value="hood">Hood</option>
              <option value="sink">Sink</option>
              <option value="faucet">Faucet</option>
              <option value="shower">Shower</option>
              <option value="bathtub">Bathtub</option>
              <option value="toilet">Toilet</option>
              <option value="bidet">Bidet</option>
              <option value="towel rack">Towel Rack</option>
              <option value="soap dispenser">Soap Dispenser</option>
              <option value="mirror">Mirror</option>
              <option value="cabinet">Cabinet</option>
              <option value="shelf">Shelf</option>
              <option value="table">Table</option>
              <option value="chair">Chair</option>
              <option value="bed">Bed</option>
              <option value="mattress">Mattress</option>
              <option value="pillow">Pillow</option>
              <option value="blanket">Blanket</option>
              <option value="curtain">Curtain</option>
              <option value="blind">Blind</option>
              <option value="carpet">Carpet</option>
              <option value="lamp">Lamp</option>
              <option value="ceiling fan">Ceiling Fan</option>
              <option value="chandelier">Chandelier</option>
              <option value="wall light">Wall Light</option>
              <option value="floor lamp">Floor Lamp</option>
              <option value="desk lamp">Desk Lamp</option>
              <option value="reading lamp">Reading Lamp</option>
              <option value="night light">Night Light</option>
              <option value="emergency light">Emergency Light</option>
              <option value="exit sign">Exit Sign</option>
              <option value="fire alarm">Fire Alarm</option>
              <option value="smoke detector">Smoke Detector</option>
              <option value="carbon monoxide detector">Carbon Monoxide Detector</option>
              <option value="radon detector">Radon Detector</option>
              <option value="water leak detector">Water Leak Detector</option>
              <option value="flood sensor">Flood Sensor</option>
              <option value="freeze sensor">Freeze Sensor</option>
              <option value="temperature sensor">Temperature Sensor</option>
              <option value="humidity sensor">Humidity Sensor</option>
              <option value="pressure sensor">Pressure Sensor</option>
              <option value="motion sensor">Motion Sensor</option>
              <option value="door sensor">Door Sensor</option>
              <option value="window sensor">Window Sensor</option>
              <option value="glass break sensor">Glass Break Sensor</option>
              <option value="vibration sensor">Vibration Sensor</option>
              <option value="tilt sensor">Tilt Sensor</option>
              <option value="shock sensor">Shock Sensor</option>
              <option value="proximity sensor">Proximity Sensor</option>
              <option value="light sensor">Light Sensor</option>
              <option value="sound sensor">Sound Sensor</option>
              <option value="gas sensor">Gas Sensor</option>
              <option value="ph sensor">pH Sensor</option>
              <option value="conductivity sensor">Conductivity Sensor</option>
              <option value="turbidity sensor">Turbidity Sensor</option>
              <option value="dissolved oxygen sensor">Dissolved Oxygen Sensor</option>
              <option value="chlorine sensor">Chlorine Sensor</option>
              <option value="fluoride sensor">Fluoride Sensor</option>
              <option value="nitrate sensor">Nitrate Sensor</option>
              <option value="phosphate sensor">Phosphate Sensor</option>
              <option value="ammonia sensor">Ammonia Sensor</option>
              <option value="chloride sensor">Chloride Sensor</option>
              <option value="sulfate sensor">Sulfate Sensor</option>
              <option value="bromide sensor">Bromide Sensor</option>
              <option value="iodide sensor">Iodide Sensor</option>
              <option value="cyanide sensor">Cyanide Sensor</option>
              <option value="arsenic sensor">Arsenic Sensor</option>
              <option value="mercury sensor">Mercury Sensor</option>
              <option value="lead sensor">Lead Sensor</option>
              <option value="cadmium sensor">Cadmium Sensor</option>
              <option value="chromium sensor">Chromium Sensor</option>
              <option value="nickel sensor">Nickel Sensor</option>
              <option value="copper sensor">Copper Sensor</option>
              <option value="zinc sensor">Zinc Sensor</option>
              <option value="iron sensor">Iron Sensor</option>
              <option value="manganese sensor">Manganese Sensor</option>
              <option value="aluminum sensor">Aluminum Sensor</option>
              <option value="silver sensor">Silver Sensor</option>
              <option value="gold sensor">Gold Sensor</option>
              <option value="platinum sensor">Platinum Sensor</option>
              <option value="palladium sensor">Palladium Sensor</option>
              <option value="rhodium sensor">Rhodium Sensor</option>
              <option value="iridium sensor">Iridium Sensor</option>
              <option value="osmium sensor">Osmium Sensor</option>
              <option value="ruthenium sensor">Ruthenium Sensor</option>
              <option value="rhenium sensor">Rhenium Sensor</option>
              <option value="tungsten sensor">Tungsten Sensor</option>
              <option value="molybdenum sensor">Molybdenum Sensor</option>
              <option value="vanadium sensor">Vanadium Sensor</option>
              <option value="titanium sensor">Titanium Sensor</option>
              <option value="zirconium sensor">Zirconium Sensor</option>
              <option value="hafnium sensor">Hafnium Sensor</option>
              <option value="niobium sensor">Niobium Sensor</option>
              <option value="tantalum sensor">Tantalum Sensor</option>
              <option value="beryllium sensor">Beryllium Sensor</option>
              <option value="lithium sensor">Lithium Sensor</option>
              <option value="sodium sensor">Sodium Sensor</option>
              <option value="potassium sensor">Potassium Sensor</option>
              <option value="rubidium sensor">Rubidium Sensor</option>
              <option value="cesium sensor">Cesium Sensor</option>
              <option value="francium sensor">Francium Sensor</option>
              <option value="magnesium sensor">Magnesium Sensor</option>
              <option value="calcium sensor">Calcium Sensor</option>
              <option value="strontium sensor">Strontium Sensor</option>
              <option value="barium sensor">Barium Sensor</option>
              <option value="radium sensor">Radium Sensor</option>
              <option value="scandium sensor">Scandium Sensor</option>
              <option value="yttrium sensor">Yttrium Sensor</option>
              <option value="lanthanum sensor">Lanthanum Sensor</option>
              <option value="cerium sensor">Cerium Sensor</option>
              <option value="praseodymium sensor">Praseodymium Sensor</option>
              <option value="neodymium sensor">Neodymium Sensor</option>
              <option value="promethium sensor">Promethium Sensor</option>
              <option value="samarium sensor">Samarium Sensor</option>
              <option value="europium sensor">Europium Sensor</option>
              <option value="gadolinium sensor">Gadolinium Sensor</option>
              <option value="terbium sensor">Terbium Sensor</option>
              <option value="dysprosium sensor">Dysprosium Sensor</option>
              <option value="holmium sensor">Holmium Sensor</option>
              <option value="erbium sensor">Erbium Sensor</option>
              <option value="thulium sensor">Thulium Sensor</option>
              <option value="ytterbium sensor">Ytterbium Sensor</option>
              <option value="lutetium sensor">Lutetium Sensor</option>
              <option value="actinium sensor">Actinium Sensor</option>
              <option value="thorium sensor">Thorium Sensor</option>
              <option value="protactinium sensor">Protactinium Sensor</option>
              <option value="uranium sensor">Uranium Sensor</option>
              <option value="neptunium sensor">Neptunium Sensor</option>
              <option value="plutonium sensor">Plutonium Sensor</option>
              <option value="americium sensor">Americium Sensor</option>
              <option value="curium sensor">Curium Sensor</option>
              <option value="berkelium sensor">Berkelium Sensor</option>
              <option value="californium sensor">Californium Sensor</option>
              <option value="einsteinium sensor">Einsteinium Sensor</option>
              <option value="fermium sensor">Fermium Sensor</option>
              <option value="mendelevium sensor">Mendelevium Sensor</option>
              <option value="nobelium sensor">Nobelium Sensor</option>
              <option value="lawrencium sensor">Lawrencium Sensor</option>
              <option value="rutherfordium sensor">Rutherfordium Sensor</option>
              <option value="dubnium sensor">Dubnium Sensor</option>
              <option value="seaborgium sensor">Seaborgium Sensor</option>
              <option value="bohrium sensor">Bohrium Sensor</option>
              <option value="hassium sensor">Hassium Sensor</option>
              <option value="meitnerium sensor">Meitnerium Sensor</option>
              <option value="darmstadtium sensor">Darmstadtium Sensor</option>
              <option value="roentgenium sensor">Roentgenium Sensor</option>
              <option value="copernicium sensor">Copernicium Sensor</option>
              <option value="nihonium sensor">Nihonium Sensor</option>
              <option value="flerovium sensor">Flerovium Sensor</option>
              <option value="moscovium sensor">Moscovium Sensor</option>
              <option value="livermorium sensor">Livermorium Sensor</option>
              <option value="tennessine sensor">Tennessine Sensor</option>
              <option value="oganesson sensor">Oganesson Sensor</option>
            </datalist>
          </div>
          <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <div class="dropdown w-100">
              <button class="btn btn-outline-primary dropdown-toggle w-100 text-start" type="button" id="dropdownCategory" data-bs-toggle="dropdown" aria-expanded="false">
                <?php
                $catName = 'Tất cả danh mục';
                foreach ($categories as $cat) {
                  if ($cat['id'] == $category_id) $catName = $cat['name'];
                }
                echo htmlspecialchars($catName);
                ?>
              </button>
              <ul class="dropdown-menu w-100" aria-labelledby="dropdownCategory" style="max-height:350px;overflow-y:auto;">
                <li><a class="dropdown-item<?php if (!$category_id) echo ' active'; ?>" href="index.php?controller=news&action=list">Tất cả danh mục</a></li>
                <?php foreach ($categories as $cat): ?>
                  <li><a class="dropdown-item<?php if ($category_id == $cat['id']) echo ' active'; ?>" href="index.php?controller=news&action=list&category_id=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <a href="index.php?controller=product&action=list" class="btn btn-outline-secondary w-100 mt-2"><i class="fas fa-times"></i> Xóa bộ lọc</a>
        </form>
      </div>
    </aside>
    <!-- Danh sách sản phẩm -->
    <section class="col-lg-9 col-md-8">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
          <span class="fw-bold">Tìm thấy <?php echo $total; ?> sản phẩm</span>
          <?php if (!empty($search)): ?>
            <span class="text-muted ms-2">cho từ khóa: "<strong><?php echo htmlspecialchars($search); ?></strong>"</span>
          <?php endif; ?>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-sort"></i> Sắp xếp
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?controller=product&action=list&sort=name">Tên A-Z</a></li>
            <li><a class="dropdown-item" href="?controller=product&action=list&sort=price_asc">Giá tăng dần</a></li>
            <li><a class="dropdown-item" href="?controller=product&action=list&sort=price_desc">Giá giảm dần</a></li>
            <li><a class="dropdown-item" href="?controller=product&action=list&sort=newest">Mới nhất</a></li>
          </ul>
        </div>
      </div>
      <?php if (!empty($products)): ?>
        <div class="row g-4">
          <?php foreach ($products as $product): ?>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
              <div class="card h-100 shadow-sm product-card border-0">
                <div class="position-relative">
                  <?php if ($product['featured']): ?>
                    <span class="badge bg-warning position-absolute top-0 start-0 m-2"><i class="fas fa-star"></i> Nổi bật</span>
                  <?php endif; ?>
                  <?php if (isset($product['stock']) && $product['stock'] <= 0): ?>
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2"><i class="fas fa-times"></i> Hết hàng</span>
                  <?php endif; ?>
                  <a href="index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>" class="d-block">
                    <?php
                    // thanhdat: chuẩn hóa đường dẫn ảnh sản phẩm
                    require_once 'helpers/image_helper.php';
                    $img = '';
                    if (!empty($product['images'][0]['image_url'])) {
                        $img = $product['images'][0]['image_url'];
                    } elseif (!empty($product['image_link'])) {
                        $img = $product['image_link'];
                    }
                    $img_url = getImageUrl($img); // thanhdat
                    if (!$img_url || $img_url === 'uploads/products/') {
                        $img_url = 'https://via.placeholder.com/300x200?text=No+Image'; // thanhdat
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($img_url); ?>" class="card-img-top product-image" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text=No+Image';">
                  </a>
                </div>
                <div class="card-body d-flex flex-column">
                  <h6 class="card-title product-title mb-1 text-truncate" title="<?php echo htmlspecialchars($product['name']); ?>"><?php echo htmlspecialchars($product['name']); ?></h6>
                  <div class="mb-2 text-muted small">
                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?>
                  </div>
                  <div class="mb-2">
                    <span class="fs-5 fw-bold text-danger"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</span>
                    <?php if (!empty($product['sale_price']) && $product['sale_price'] > 0): ?>
                      <span class="text-muted ms-2"><del><?php echo number_format($product['sale_price'], 0, ',', '.'); ?> đ</del></span>
                    <?php endif; ?>
                  </div>
                  <div class="mt-auto d-flex gap-2">
                    <a href="index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-eye"></i> Xem chi tiết</a>
                    <?php if (isset($product['stock']) && $product['stock'] > 0): ?>
                      <form method="POST" action="index.php?controller=cart&action=add" class="add-to-cart-form" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-outline-success btn-sm" title="Thêm vào giỏ hàng"><i class="fas fa-cart-plus"></i></button>
                      </form>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Phân trang đẹp -->
        <?php if ($totalPages > 1): ?>
          <?php
          $queryBase = "?controller=product&action=list";
          if ($search !== '') $queryBase .= "&search=" . urlencode($search);
          if ($category_id) $queryBase .= "&category_id=" . urlencode($category_id);
          if (!empty($_GET['sort'])) $queryBase .= "&sort=" . urlencode($_GET['sort']);
          $range = 2;
          $showDots = false;
          ?>
          <nav aria-label="Phân trang" class="mt-4">
            <ul class="pagination justify-content-center pagination-lg">
              <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="<?php echo $queryBase . '&page=' . ($page-1); ?>"><i class="fas fa-chevron-left"></i></a></li>
              <?php endif; ?>
              <?php for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == 1 || $i == $totalPages || ($i >= $page - $range && $i <= $page + $range)) {
                  if ($showDots) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    $showDots = false;
                  }
                  echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="' . $queryBase . '&page=' . $i . '">' . $i . '</a></li>';
                } elseif (!$showDots) {
                  $showDots = true;
                }
              } ?>
              <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="<?php echo $queryBase . '&page=' . ($page+1); ?>"><i class="fas fa-chevron-right"></i></a></li>
              <?php endif; ?>
            </ul>
            <div class="text-center text-muted small mt-2">Trang <?php echo $page; ?> / <?php echo $totalPages; ?> (<?php echo $total; ?> sản phẩm)</div>
          </nav>
        <?php endif; ?>
      <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-search fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">Không tìm thấy sản phẩm nào</h5>
          <p class="text-muted">Hãy thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác</p>
          <a href="index.php?controller=product&action=list" class="btn btn-primary"><i class="fas fa-home"></i> Xem tất cả sản phẩm</a>
        </div>
      <?php endif; ?>
    </section>
  </div>
</div>
<style>
.product-card {transition: box-shadow 0.2s, transform 0.2s; border-radius: 1rem;}
.product-card:hover {box-shadow: 0 8px 32px rgba(0,0,0,0.15)!important; transform: translateY(-4px) scale(1.01);}
.product-image {height: 220px; object-fit: cover; border-radius: 1rem 1rem 0 0; background: #f8f9fa;}
.product-title {font-size: 1.1rem; font-weight: 600; color: #222; line-height: 1.4;}
@media (max-width: 768px) {.product-image {height: 160px;}}
.container-fluid, .row, aside.col-lg-3, aside.col-md-4 {
  padding-left: 0 !important;
  margin-left: 0 !important;
}
aside.col-lg-3, aside.col-md-4, aside {background: none !important; border: none !important;}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.add-to-cart-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);
      fetch('index.php?controller=cart&action=add', {
        method: 'POST',
        body: formData
      })
      .then(res => res.ok ? res.text() : Promise.reject(res))
      .then(() => {
        showCartToast('Đã thêm vào giỏ hàng!');
        // Cập nhật badge giỏ hàng
        fetch('index.php?controller=cart&action=count')
          .then(r => r.json())
          .then(data => {
            const badge = document.querySelector('.btn-cart-badge');
            if (badge) badge.textContent = data.count > 0 ? data.count : '';
          });
      });
    });
  });
});
function showCartToast(msg) {
  let toast = document.getElementById('cart-toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'cart-toast';
    toast.className = 'toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-4 show';
    toast.style.zIndex = 9999;
    toast.innerHTML = '<div class="d-flex"><div class="toast-body">' + msg + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    document.body.appendChild(toast);
    toast.querySelector('.btn-close').onclick = () => toast.remove();
    setTimeout(() => { if (toast) toast.remove(); }, 2000);
  }
}
</script> 