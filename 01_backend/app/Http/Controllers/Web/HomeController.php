<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Evaluation;
use App\Models\Criteria;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Nếu đã đăng nhập, chuyển đến dashboard
        if ($request->user()) {
            return redirect()->route('dashboard');
        }

        // Thống kê cho trang chủ
        $stats = [
            'total_domains' => Domain::count(),
            'total_criteria' => Criteria::count(),
            'total_evaluations' => Evaluation::count(),
            'avg_score' => round(Evaluation::avg('percentage'), 2),
            'happy_clients' => 500,
            'years_experience' => 5,
        ];

        // Các lĩnh vực nổi bật
        $featuredDomains = Domain::withCount('criteria')
            ->limit(6)
            ->get();

        // Các tính năng chính
        $features = [
            [
                'icon' => 'shield',
                'title' => 'Bảo mật đa lớp',
                'description' => 'FHHE encryption, MFA, OTP, Key security, bảo vệ dữ liệu tối đa',
                'color' => 'blue',
            ],
            [
                'icon' => 'chart-line',
                'title' => '400+ tiêu chí đánh giá',
                'description' => 'Đánh giá toàn diện 20 lĩnh vực bảo mật theo chuẩn quốc tế',
                'color' => 'green',
            ],
            [
                'icon' => 'mobile-alt',
                'title' => 'Đa nền tảng',
                'description' => 'Hỗ trợ Web, Android, iOS, đồng bộ dữ liệu real-time',
                'color' => 'purple',
            ],
            [
                'icon' => 'brain',
                'title' => 'AI thông minh',
                'description' => 'Gợi ý cải thiện, dự đoán xu hướng, phát hiện bất thường',
                'color' => 'orange',
            ],
            [
                'icon' => 'users',
                'title' => '10 vai trò',
                'description' => 'Phân quyền chi tiết theo chức năng, bảo mật nghiêm ngặt',
                'color' => 'red',
            ],
            [
                'icon' => 'chart-pie',
                'title' => 'Báo cáo chuyên sâu',
                'description' => 'Dashboard trực quan, biểu đồ động, xuất file PDF/Excel',
                'color' => 'teal',
            ],
        ];

        // Các gói dịch vụ (5 version)
        $plans = [
            [
                'version' => 'v1',
                'name' => 'SME',
                'price' => 'Liên hệ',
                'price_old' => null,
                'features' => ['10 người dùng', 'Đánh giá cơ bản', 'Báo cáo PDF', 'Email hỗ trợ'],
                'recommended' => false,
                'color' => 'gray',
            ],
            [
                'version' => 'v2',
                'name' => 'Mid-market',
                'price' => 'Liên hệ',
                'price_old' => null,
                'features' => ['50 người dùng', 'Phân tích nâng cao', 'API hỗ trợ', 'Báo cáo Excel'],
                'recommended' => true,
                'color' => 'blue',
            ],
            [
                'version' => 'v3',
                'name' => 'Enterprise',
                'price' => 'Liên hệ',
                'price_old' => null,
                'features' => ['200 người dùng', 'Đồng bộ real-time', 'Gợi ý AI', 'Hỗ trợ 24/7'],
                'recommended' => false,
                'color' => 'purple',
            ],
            [
                'version' => 'v4',
                'name' => 'Finance',
                'price' => 'Liên hệ',
                'price_old' => null,
                'features' => ['500 người dùng', 'Kiểm tra tuân thủ', 'Ma trận rủi ro', 'Bảo mật nâng cao'],
                'recommended' => false,
                'color' => 'orange',
            ],
            [
                'version' => 'v5',
                'name' => 'Government',
                'price' => 'Liên hệ',
                'price_old' => null,
                'features' => ['Không giới hạn', 'Giám sát Dark Web', 'Tình báo đe dọa', 'Bảo mật cấp quốc phòng'],
                'recommended' => false,
                'color' => 'red',
            ],
        ];

        // Đối tác tin cậy
        $partners = [
            ['name' => 'Viettel', 'logo' => 'viettel.png'],
            ['name' => 'VNPT', 'logo' => 'vnpt.png'],
            ['name' => 'FPT', 'logo' => 'fpt.png'],
            ['name' => 'CMC', 'logo' => 'cmc.png'],
        ];

        return view('welcome', compact(
            'stats',
            'featuredDomains',
            'features',
            'plans',
            'partners'
        ));
    }

    public function about()
    {
        $team = [
            ['name' => 'Nguyễn Văn A', 'position' => 'CEO & Founder', 'bio' => 'Chuyên gia bảo mật với 10+ năm kinh nghiệm', 'avatar' => 'avatar1.jpg'],
            ['name' => 'Trần Thị B', 'position' => 'CTO', 'bio' => 'Kiến trúc sư hệ thống, chuyên gia AI', 'avatar' => 'avatar2.jpg'],
            ['name' => 'Lê Văn C', 'position' => 'Head of Security', 'bio' => 'Chuyên gia an ninh mạng, CEH, OSCP', 'avatar' => 'avatar3.jpg'],
        ];

        $milestones = [
            ['year' => 2020, 'title' => 'Thành lập công ty', 'description' => 'Ra mắt phiên bản đầu tiên'],
            ['year' => 2021, 'title' => 'Đạt chứng chỉ ISO 27001', 'description' => 'Được cấp chứng chỉ quốc tế'],
            ['year' => 2022, 'title' => 'Ra mắt AI Scoring', 'description' => 'Tích hợp trí tuệ nhân tạo'],
            ['year' => 2023, 'title' => 'Mở rộng thị trường', 'description' => 'Có mặt tại 5 quốc gia'],
            ['year' => 2024, 'title' => 'Phiên bản 5.0', 'description' => 'Tích hợp bảo mật FHHE'],
        ];

        return view('about', compact('team', 'milestones'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function features()
    {
        $features = [
            'security' => [
                'title' => 'Bảo mật đa lớp',
                'items' => [
                    ['name' => 'FHHE Encryption', 'description' => 'Mã hóa đầu cuối cấp độ quốc phòng'],
                    ['name' => 'MFA', 'description' => 'Xác thực đa yếu tố với OTP và Key'],
                    ['name' => 'Audit Log', 'description' => 'Ghi lại toàn bộ hoạt động hệ thống'],
                    ['name' => 'Session Management', 'description' => 'Quản lý phiên đăng nhập an toàn'],
                ],
            ],
            'evaluation' => [
                'title' => 'Đánh giá toàn diện',
                'items' => [
                    ['name' => '400+ tiêu chí', 'description' => 'Đánh giá chi tiết 20 lĩnh vực'],
                    ['name' => 'AI Scoring', 'description' => 'Chấm điểm tự động thông minh'],
                    ['name' => 'Real-time', 'description' => 'Cập nhật kết quả tức thì'],
                    ['name' => 'Gap Analysis', 'description' => 'Phân tích khoảng cách cải thiện'],
                ],
            ],
            'report' => [
                'title' => 'Báo cáo chuyên sâu',
                'items' => [
                    ['name' => 'Dashboard', 'description' => 'Tổng quan dữ liệu trực quan'],
                    ['name' => 'Export PDF/Excel', 'description' => 'Xuất báo cáo đa định dạng'],
                    ['name' => 'Scheduled Reports', 'description' => 'Tự động gửi báo cáo định kỳ'],
                    ['name' => 'Charts & Graphs', 'description' => 'Biểu đồ phân tích chuyên sâu'],
                ],
            ],
            'integration' => [
                'title' => 'Tích hợp linh hoạt',
                'items' => [
                    ['name' => 'RESTful API', 'description' => 'Tích hợp dễ dàng với hệ thống khác'],
                    ['name' => 'Webhook', 'description' => 'Gửi dữ liệu real-time'],
                    ['name' => 'SSO', 'description' => 'Đăng nhập tập trung'],
                    ['name' => 'LDAP/SAML', 'description' => 'Hỗ trợ xác thực doanh nghiệp'],
                ],
            ],
        ];

        return view('features', compact('features'));
    }

    public function pricing()
    {
        $plans = [
            'v1' => [
                'name' => 'SME',
                'price' => 'Liên hệ',
                'price_monthly' => null,
                'features' => [
                    '✓ 10 người dùng',
                    '✓ Đánh giá cơ bản',
                    '✓ Báo cáo PDF',
                    '✓ Email hỗ trợ',
                    '✗ API truy cập',
                    '✗ AI Scoring',
                    '✗ Đồng bộ real-time',
                ],
                'button_text' => 'Dùng thử',
                'button_class' => 'btn-outline',
            ],
            'v2' => [
                'name' => 'Mid-market',
                'price' => 'Liên hệ',
                'price_monthly' => null,
                'features' => [
                    '✓ 50 người dùng',
                    '✓ Đánh giá nâng cao',
                    '✓ Báo cáo PDF/Excel',
                    '✓ API truy cập',
                    '✓ Gợi ý cải thiện',
                    '✗ AI Scoring',
                    '✗ Đồng bộ real-time',
                ],
                'button_text' => 'Dùng thử',
                'button_class' => 'btn-primary',
            ],
            'v3' => [
                'name' => 'Enterprise',
                'price' => 'Liên hệ',
                'price_monthly' => null,
                'features' => [
                    '✓ 200 người dùng',
                    '✓ Đánh giá toàn diện',
                    '✓ Báo cáo PDF/Excel/JSON',
                    '✓ API truy cập',
                    '✓ AI Scoring',
                    '✓ Đồng bộ real-time',
                    '✓ Hỗ trợ 24/7',
                ],
                'button_text' => 'Liên hệ',
                'button_class' => 'btn-primary',
            ],
            'v4' => [
                'name' => 'Finance',
                'price' => 'Liên hệ',
                'price_monthly' => null,
                'features' => [
                    '✓ 500 người dùng',
                    '✓ Đánh giá tuân thủ',
                    '✓ Báo cáo chuyên sâu',
                    '✓ API + Webhook',
                    '✓ AI Scoring nâng cao',
                    '✓ Đồng bộ real-time',
                    '✓ Kiểm tra tuân thủ',
                    '✓ Ma trận rủi ro',
                ],
                'button_text' => 'Liên hệ',
                'button_class' => 'btn-primary',
            ],
            'v5' => [
                'name' => 'Government',
                'price' => 'Liên hệ',
                'price_monthly' => null,
                'features' => [
                    '✓ Không giới hạn người dùng',
                    '✓ Đánh giá cấp quốc phòng',
                    '✓ Báo cáo bảo mật tối đa',
                    '✓ API + Webhook + SSO',
                    '✓ AI Scoring cao cấp',
                    '✓ Đồng bộ real-time',
                    '✓ Giám sát Dark Web',
                    '✓ Tình báo đe dọa',
                    '✓ Bảo mật FHHE',
                ],
                'button_text' => 'Liên hệ',
                'button_class' => 'btn-primary',
            ],
        ];

        $faqs = [
            ['question' => 'Có bản dùng thử miễn phí không?', 'answer' => 'Có, chúng tôi cung cấp bản dùng thử 14 ngày cho tất cả các gói.'],
            ['question' => 'Có hỗ trợ triển khai on-premise không?', 'answer' => 'Có, chúng tôi hỗ trợ triển khai on-premise cho khách hàng doanh nghiệp.'],
            ['question' => 'Có thể nâng cấp gói bất cứ lúc nào không?', 'answer' => 'Có, bạn có thể nâng cấp gói bất cứ lúc nào.'],
            ['question' => 'Dữ liệu có được bảo mật không?', 'answer' => 'Có, dữ liệu được mã hóa đầu cuối với chuẩn FHHE.'],
        ];

        return view('pricing', compact('plans', 'faqs'));
    }

    public function documentation()
    {
        $docs = [
            'getting-started' => [
                'title' => 'Bắt đầu',
                'items' => [
                    ['title' => 'Giới thiệu', 'url' => route('docs.intro')],
                    ['title' => 'Cài đặt', 'url' => route('docs.installation')],
                    ['title' => 'Cấu hình', 'url' => route('docs.configuration')],
                ],
            ],
            'api' => [
                'title' => 'API Reference',
                'items' => [
                    ['title' => 'Authentication', 'url' => route('docs.api.auth')],
                    ['title' => 'Endpoints', 'url' => route('docs.api.endpoints')],
                    ['title' => 'Webhooks', 'url' => route('docs.api.webhooks')],
                ],
            ],
            'guides' => [
                'title' => 'Hướng dẫn',
                'items' => [
                    ['title' => 'Tạo đánh giá', 'url' => route('docs.guide.evaluation')],
                    ['title' => 'Quản lý người dùng', 'url' => route('docs.guide.users')],
                    ['title' => 'Báo cáo', 'url' => route('docs.guide.reports')],
                ],
            ],
        ];

        return view('documentation', compact('docs'));
    }

    public function terms()
    {
        return view('terms');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // Gửi email
        // Mail::to('contact@security-system.com')->send(new ContactMail($request->all()));

        return redirect()->route('contact')->with('success', 'Cảm ơn bạn đã liên hệ!');
    }
}