<?php
// controller/AddressController.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Address.php';

class AddressController {
    private $addressModel;
    
    public function __construct() {
        $this->addressModel = new Address();
    }
    
    public function getProvinces() {
        $apiUrl = 'https://provinces.open-api.vn/api/';
        
        // Log request
        $logMessage = "[AddressController] Gọi API lấy danh sách tỉnh/thành phố\n";
        $logMessage .= "- URL: $apiUrl\n";
        $logMessage .= "- Thời gian: " . date('Y-m-d H:i:s') . "\n";
        
        $startTime = microtime(true);
        $response = file_get_contents($apiUrl);
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000, 2);
        
        // Log response
        $logMessage .= "- Thời gian phản hồi: {$responseTime}ms\n";
        
        if ($response === false) {
            $error = 'Failed to fetch provinces';
            $logMessage .= "- Lỗi: Không thể lấy dữ liệu tỉnh/thành phố\n";
            error_log($logMessage);
            http_response_code(500);
            echo json_encode(['error' => $error]);
            return;
        }
        
        // Log kết quả thành công
        $data = json_decode($response, true);
        $logMessage .= "- Trạng thái: Thành công\n";
        $logMessage .= "- Số lượng tỉnh/thành phố: " . (is_array($data) ? count($data) : 0) . "\n";
        $logMessage .= "- Dữ liệu mẫu: " . json_encode(is_array($data) ? array_slice($data, 0, 3) : []) . "\n";
        error_log($logMessage);
        
        header('Content-Type: application/json');
        echo $response;
    }
    
    public function getDistricts($provinceId = null) {
        // Lấy provinceId từ tham số URL
        $provinceCode = $provinceId ?? ($_GET['code'] ?? null);
        if (!$provinceCode) {
            $error = 'Missing province code';
            error_log("[AddressController] Lỗi: $error");
            http_response_code(400);
            echo json_encode(['error' => $error]);
            return;
        }
        
        $apiUrl = "https://provinces.open-api.vn/api/p/" . urlencode($provinceCode) . "?depth=2";
        
        // Log request
        $logMessage = "[AddressController] Gọi API lấy danh sách quận/huyện\n";
        $logMessage .= "- URL: $apiUrl\n";
        $logMessage .= "- Mã tỉnh: $provinceCode\n";
        $logMessage .= "- Thời gian: " . date('Y-m-d H:i:s') . "\n";
        
        $startTime = microtime(true);
        $response = @file_get_contents($apiUrl);
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000, 2); // Thời gian phản hồi tính bằng ms
        
        // Log response
        $logMessage .= "- Thời gian phản hồi: {$responseTime}ms\n";
        
        if ($response === false) {
            $error = 'Failed to fetch districts';
            $logMessage .= "- Lỗi: Không thể lấy dữ liệu quận/huyện\n";
            error_log($logMessage);
            http_response_code(500);
            echo json_encode(['error' => $error]);
            return;
        }
        
        // Log kết quả thành công
        $responseData = json_decode($response, true);
        $logMessage .= "- Trạng thái: Thành công\n";
        $logMessage .= "- Số lượng quận/huyện: " . (isset($responseData['districts']) ? count($responseData['districts']) : 0) . "\n";
        $logMessage .= "- Dữ liệu mẫu: " . json_encode(isset($responseData['districts']) ? array_slice($responseData['districts'], 0, 3) : []) . "\n";
        error_log($logMessage);
        
        header('Content-Type: application/json');
        echo $response;
    }
    
    public function save() {
        // Kiểm tra đăng nhập
        if (empty($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để thực hiện thao tác này']);
            return;
        }

        // Kiểm tra phương thức POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
            return;
        }

        // Lấy dữ liệu từ form
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address_line1' => trim($_POST['address_line1'] ?? ''),
            'address_line2' => trim($_POST['address_line2'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'district' => trim($_POST['district'] ?? ''),
            'ward' => trim($_POST['ward'] ?? ''),
            'is_default' => isset($_POST['is_default']) ? 1 : 0
        ];

        // Validate dữ liệu
        $errors = [];
        if (empty($data['first_name'])) $errors[] = 'Vui lòng nhập họ';
        if (empty($data['last_name'])) $errors[] = 'Vui lòng nhập tên';
        if (empty($data['phone'])) $errors[] = 'Vui lòng nhập số điện thoại';
        if (empty($data['address_line1'])) $errors[] = 'Vui lòng nhập địa chỉ';
        if (empty($data['city'])) $errors[] = 'Vui lòng chọn tỉnh/thành phố';
        if (empty($data['district'])) $errors[] = 'Vui lòng chọn quận/huyện';
        if (empty($data['ward'])) $errors[] = 'Vui lòng chọn phường/xã';

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => implode('<br>', $errors)]);
            return;
        }

        try {
            // Lưu địa chỉ
            $result = $this->addressModel->save($_SESSION['user']['id'], $data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Lưu địa chỉ thành công']);
            } else {
                throw new Exception('Không thể lưu địa chỉ');
            }
        } catch (Exception $e) {
            error_log("Lỗi khi lưu địa chỉ: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi lưu địa chỉ. Vui lòng thử lại sau.']);
        }
    }
    
    public function getWards($districtId = null) {
        // Lấy districtId từ tham số URL
        $districtCode = $districtId ?? ($_GET['code'] ?? null);
        if (!$districtCode) {
            $error = 'Missing district code';
            error_log("[AddressController] Lỗi: $error");
            http_response_code(400);
            echo json_encode(['error' => $error]);
            return;
        }
        
        $apiUrl = "https://provinces.open-api.vn/api/d/" . urlencode($districtCode) . "?depth=2";
        
        // Log request
        $logMessage = "[AddressController] Gọi API lấy danh sách phường/xã\n";
        $logMessage .= "- URL: $apiUrl\n";
        $logMessage .= "- Mã quận/huyện: $districtCode\n";
        $logMessage .= "- Thời gian: " . date('Y-m-d H:i:s') . "\n";
        
        $startTime = microtime(true);
        
        // Log request
        $logMessage = "[AddressController] Gọi API lấy danh sách phường/xã\n";
        $logMessage .= "- URL: $apiUrl\n";
        $logMessage .= "- Mã quận/huyện: $districtCode\n";
        $logMessage .= "- Thời gian: " . date('Y-m-d H:i:s') . "\n";
        
        $startTime = microtime(true);
        $response = @file_get_contents($apiUrl);
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000, 2); // Thời gian phản hồi tính bằng ms
        
        // Log response
        $logMessage .= "- Thời gian phản hồi: {$responseTime}ms\n";
        
        if ($response === false) {
            $error = 'Failed to fetch wards';
            $logMessage .= "- Lỗi: Không thể lấy dữ liệu phường/xã\n";
            error_log($logMessage);
            http_response_code(500);
            echo json_encode(['error' => $error]);
            return;
        }
        
        // Log kết quả thành công
        $responseData = json_decode($response, true);
        $logMessage .= "- Trạng thái: Thành công\n";
        $logMessage .= "- Số lượng phường/xã: " . (isset($responseData['wards']) ? count($responseData['wards']) : 0) . "\n";
        $logMessage .= "- Dữ liệu mẫu: " . json_encode(isset($responseData['wards']) ? array_slice($responseData['wards'], 0, 3) : []) . "\n";
        error_log($logMessage);
        
        header('Content-Type: application/json');
        echo $response;
    }
    

    public function getUserAddresses() {
        if (empty($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $addresses = $this->addressModel->getUserAddresses($_SESSION['user']['id']);
        echo json_encode(['success' => true, 'data' => $addresses]);
    }
    
    public function setDefault() {
        if (empty($_SESSION['user']) || empty($_POST['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }
        
        $result = $this->addressModel->setDefaultAddress($_POST['id'], $_SESSION['user']['id']);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Đặt địa chỉ mặc định thành công']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }
    
    public function delete() {
        if (empty($_SESSION['user']) || empty($_POST['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }
        
        $result = $this->addressModel->deleteAddress($_POST['id'], $_SESSION['user']['id']);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Xóa địa chỉ thành công']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa địa chỉ']);
        }
    }
}

// Router
if (isset($_GET['action'])) {
    $controller = new AddressController();
    $action = $_GET['action'];
    
    switch ($action) {
        case 'getProvinces':
            $controller->getProvinces();
            break;
            
        case 'getDistricts':
            if (isset($_GET['provinceId'])) {
                $controller->getDistricts($_GET['provinceId']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Province ID is required']);
            }
            break;
            
        case 'getWards':
            if (isset($_GET['districtId'])) {
                $controller->getWards($_GET['districtId']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'District ID is required']);
            }
            break;
            
        case 'save':
            $controller->save();
            break;
            
        case 'getUserAddresses':
            $controller->getUserAddresses();
            break;
            
        case 'setDefault':
            $controller->setDefault();
            break;
            
        case 'delete':
            $controller->delete();
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Action not found']);
    }
}
