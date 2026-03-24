<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\PortfolioItem;
use App\Models\Profile;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $publicDiskUrl = static fn (string $path): string => Storage::disk('public')->url($path);

        User::query()->updateOrCreate(
            ['role' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'rahmatfauji@gmail.com',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
            ]
        );

        // Keep user accounts, reset content tables so seed output is deterministic.
        Slide::query()->delete();
        PortfolioItem::query()->delete();
        BlogPost::query()->delete();
        Profile::query()->delete();

        // Create branded local SVG assets so seeded content does not depend on external URLs.
        $this->seedBrandImage('uploads/profile/seed-avatar.svg', '#0F4C81', '#35A7FF', 'RF', 'Avatar');

        for ($i = 1; $i <= 10; $i++) {
            $this->seedBrandImage('uploads/blog/seed-blog-' . $i . '.svg', '#1D3557', '#2A9D8F', 'BLOG', 'Article ' . $i);
        }

        for ($i = 1; $i <= 8; $i++) {
            $this->seedBrandImage('uploads/portfolio/seed-portfolio-' . $i . '.svg', '#264653', '#F4A261', 'WORK', 'Project ' . $i);
        }

        for ($i = 1; $i <= 4; $i++) {
            $this->seedBrandImage('uploads/slide/seed-slide-' . $i . '.svg', '#1B4332', '#95D5B2', 'RAHMAT', 'Slide ' . $i);
        }

        Profile::query()->create([
            'full_name' => 'Rahmat Fauji',
            'title' => 'Web Developer & Data Analytics Builder',
            'bio' => 'I build modern web applications and data products that help teams move faster. My focus is clean architecture, practical UI/UX, and reliable delivery from idea to production.',
            'email' => 'rahmat@example.com',
            'phone' => '+62 812-0000-0000',
            'location' => 'Indonesia',
            'linkedin_url' => 'https://www.linkedin.com/in/rahmat-fauji',
            'github_url' => 'https://github.com/rahmat-fauji',
            'avatar_url' => $publicDiskUrl('uploads/profile/seed-avatar.svg'),
            'chart_label_1' => 'Data Modeling',
            'chart_value_1' => 35,
            'chart_label_2' => 'Dashboard Design',
            'chart_value_2' => 25,
            'chart_label_3' => 'Business Insight',
            'chart_value_3' => 20,
            'chart_label_4' => 'Automation & Governance',
            'chart_value_4' => 20,
            'expertise_chart' => [
                ['label' => 'Data Modeling', 'value' => 28, 'color' => '#0F4C81'],
                ['label' => 'Power BI', 'value' => 22, 'color' => '#2A72D6'],
                ['label' => 'Dashboard Design', 'value' => 16, 'color' => '#35A7FF'],
                ['label' => 'Business Insight', 'value' => 14, 'color' => '#3AAFA9'],
                ['label' => 'Automation', 'value' => 12, 'color' => '#F4A261'],
                ['label' => 'Governance', 'value' => 8, 'color' => '#E76F51'],
            ],
        ]);

        $blogPosts = [
            [
                'slug' => 'power-bi-executive-dashboard-design-framework',
                'title' => 'Power BI Executive Dashboard Design Framework',
                'excerpt' => 'A practical framework to design decision-ready dashboards for executives.',
                'content' => '<p>The strongest executive dashboards combine KPI hierarchy, context-rich trends, and clear drill paths. Start with strategic metrics, then add operational drivers so leaders can move from signal to action in minutes.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-1.svg'),
                'published_at' => Carbon::now()->subDays(12),
                'is_published' => true,
            ],
            [
                'slug' => 'power-query-patterns-for-messy-business-data',
                'title' => 'Power Query Patterns for Messy Business Data',
                'excerpt' => 'Reusable Power Query techniques for inconsistent files and manual reports.',
                'content' => '<p>Most analytics delays come from unstable source data. Build repeatable Power Query steps for schema validation, type enforcement, and null handling to create robust datasets that refresh reliably.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-2.svg'),
                'published_at' => Carbon::now()->subDays(10),
                'is_published' => true,
            ],
            [
                'slug' => 'dax-measures-that-improve-kpi-accuracy',
                'title' => 'DAX Measures That Improve KPI Accuracy',
                'excerpt' => 'Essential DAX patterns to avoid misleading KPI calculations.',
                'content' => '<p>KPI trust depends on calculation quality. Use explicit filter context, robust time-intelligence patterns, and separate base vs display measures to keep KPIs stable across visual interactions.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-3.svg'),
                'published_at' => Carbon::now()->subDays(8),
                'is_published' => true,
            ],
            [
                'slug' => 'building-row-level-security-in-power-bi',
                'title' => 'Building Row-Level Security in Power BI',
                'excerpt' => 'How to implement scalable row-level security for multi-team reporting.',
                'content' => '<p>RLS should protect data without hurting usability. Map user identities to business entities, validate role behavior with test personas, and document exceptions to keep governance transparent.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-4.svg'),
                'published_at' => Carbon::now()->subDays(7),
                'is_published' => true,
            ],
            [
                'slug' => 'power-bi-performance-tuning-checklist',
                'title' => 'Power BI Performance Tuning Checklist',
                'excerpt' => 'A checklist to reduce slow visuals and long refresh times.',
                'content' => '<p>Performance tuning starts with model design. Reduce cardinality, optimize relationships, disable unnecessary interactions, and use aggregations to keep dashboards responsive for end users.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-5.svg'),
                'published_at' => Carbon::now()->subDays(6),
                'is_published' => true,
            ],
            [
                'slug' => 'automating-monthly-reporting-with-power-automate',
                'title' => 'Automating Monthly Reporting with Power Automate',
                'excerpt' => 'Cut manual reporting cycles with flow-based automation.',
                'content' => '<p>Power Automate can orchestrate data extraction, approvals, and stakeholder notifications. Define trigger boundaries and retry logic carefully to avoid silent failures in critical workflows.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-6.svg'),
                'published_at' => Carbon::now()->subDays(5),
                'is_published' => true,
            ],
            [
                'slug' => 'power-apps-for-data-collection-at-scale',
                'title' => 'Power Apps for Data Collection at Scale',
                'excerpt' => 'Designing Power Apps forms that stay reliable across teams and regions.',
                'content' => '<p>Successful data-collection apps prioritize validation, offline handling, and clean submission tracking. Standardize field logic early so downstream analytics remains consistent and audit-friendly.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-7.svg'),
                'published_at' => Carbon::now()->subDays(4),
                'is_published' => true,
            ],
            [
                'slug' => 'from-excel-to-governed-power-bi-semantic-model',
                'title' => 'From Excel to a Governed Power BI Semantic Model',
                'excerpt' => 'A transition strategy from spreadsheet chaos to governed analytics.',
                'content' => '<p>Move critical logic from spreadsheets into a shared semantic model. Define naming conventions, reusable measures, and ownership boundaries so business users can self-serve with confidence.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-8.svg'),
                'published_at' => Carbon::now()->subDays(3),
                'is_published' => true,
            ],
            [
                'slug' => 'kpi-storytelling-techniques-for-power-bi',
                'title' => 'KPI Storytelling Techniques for Power BI',
                'excerpt' => 'Turn raw KPI visuals into clear narratives for business stakeholders.',
                'content' => '<p>Good storytelling in BI means sequencing visuals by business questions. Lead with performance status, explain drivers, and close with actions so decision-makers know exactly what to do next.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-9.svg'),
                'published_at' => Carbon::now()->subDays(2),
                'is_published' => true,
            ],
            [
                'slug' => 'power-bi-governance-model-for-growing-teams',
                'title' => 'A Power BI Governance Model for Growing Teams',
                'excerpt' => 'A lightweight governance model that scales without slowing delivery.',
                'content' => '<p>Define workspace standards, deployment flow, certification labels, and ownership maps. Governance should accelerate quality and trust, not create unnecessary reporting bottlenecks.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-10.svg'),
                'published_at' => Carbon::now()->subDay(),
                'is_published' => true,
            ],
        ];

        foreach ($blogPosts as $post) {
            BlogPost::query()->create($post);
        }

        $portfolioItems = [
            [
                'title' => 'Sales Performance Command Center',
                'category' => 'Power BI Dashboard',
                'summary' => 'Executive dashboard for daily sales, margin, and regional contribution analysis.',
                'description' => 'Built a semantic model with territory and product dimensions, plus drill-through pages for sales managers to investigate underperforming segments quickly.',
                'project_url' => 'https://example.com/powerbi-sales-command-center',
                'image_url' => $publicDiskUrl('uploads/portfolio/seed-portfolio-1.svg'),
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Finance KPI Suite for CFO Office',
                'category' => 'Power BI Dashboard',
                'summary' => 'A full finance dashboard suite covering revenue, OPEX, EBITDA, and variance analysis.',
                'description' => 'Implemented standardized DAX measures and month-end reconciliation views to align finance reporting across planning, accounting, and leadership teams.',
                'project_url' => 'https://example.com/powerbi-finance-kpi-suite',
                'image_url' => $publicDiskUrl('uploads/portfolio/seed-portfolio-2.svg'),
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Inventory Health & Forecast Dashboard',
                'category' => 'Power BI Analytics',
                'summary' => 'Monitors stock aging, stockout risk, and demand forecasts in one operational view.',
                'description' => 'Combined ERP data and forecast assumptions to surface reorder priorities and reduce excess stock while maintaining service levels.',
                'project_url' => 'https://example.com/powerbi-inventory-health',
                'image_url' => $publicDiskUrl('uploads/portfolio/seed-portfolio-3.svg'),
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'HR Workforce Analytics Dashboard',
                'category' => 'People Analytics',
                'summary' => 'Tracks hiring funnel, turnover, and workforce composition for HR strategy.',
                'description' => 'Designed role-based views for HRBP, recruiters, and leadership with reliable monthly snapshots and trend metrics.',
                'project_url' => 'https://example.com/powerbi-hr-workforce-analytics',
                'image_url' => $publicDiskUrl('uploads/portfolio/seed-portfolio-4.svg'),
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Power Apps Field Audit Solution',
                'category' => 'Power Apps',
                'summary' => 'Mobile app for field teams to submit structured audit findings in real time.',
                'description' => 'Built validation rules, photo capture, and offline-friendly forms. Data feeds directly into Power BI for compliance and quality tracking.',
                'project_url' => 'https://example.com/powerapps-field-audit',
                'image_url' => $publicDiskUrl('uploads/portfolio/seed-portfolio-5.svg'),
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Procurement Request App with Approval Flow',
                'category' => 'Power Apps + Power Automate',
                'summary' => 'End-to-end procurement request app with approval routing and SLA visibility.',
                'description' => 'Integrated Power Apps forms, approval workflows, and Power BI monitoring to reduce cycle time and improve procurement governance.',
                'project_url' => 'https://example.com/powerapps-procurement-flow',
                'image_url' => $publicDiskUrl('uploads/portfolio/seed-portfolio-6.svg'),
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Service Desk SLA Monitoring',
                'category' => 'Operational Analytics',
                'summary' => 'SLA and ticket flow analytics for IT service management teams.',
                'description' => 'Implemented queue aging, breach prediction, and workload balancing visuals to improve response time and customer satisfaction.',
                'project_url' => 'https://example.com/service-desk-sla-monitoring',
                'image_url' => $publicDiskUrl('uploads/portfolio/seed-portfolio-7.svg'),
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Power BI Governance & Adoption Program',
                'category' => 'BI Strategy',
                'summary' => 'Governance blueprint with workspace standards, certification flow, and adoption metrics.',
                'description' => 'Developed a scalable governance operating model and measured adoption through active user, report quality, and business outcome indicators.',
                'project_url' => 'https://example.com/powerbi-governance-adoption',
                'image_url' => $publicDiskUrl('uploads/portfolio/seed-portfolio-8.svg'),
                'display_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($portfolioItems as $item) {
            PortfolioItem::query()->create($item);
        }

        $slides = [
            [
                'title' => 'Data Analytics for Better Decisions',
                'subtitle' => 'Turn raw data into clear insights that accelerate business decisions.',
                'image_url' => $publicDiskUrl('uploads/slide/seed-slide-1.svg'),
                'button_text' => 'Explore Dashboard',
                'button_url' => url('/portfolio'),
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Real-Time KPI Monitoring',
                'subtitle' => 'Monitor team and product performance in real time through clear data visualizations.',
                'image_url' => $publicDiskUrl('uploads/slide/seed-slide-2.svg'),
                'button_text' => 'View Portfolio',
                'button_url' => url('/portfolio'),
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Predictive Insights & Growth',
                'subtitle' => 'Use historical trends to predict new opportunities and strengthen growth strategy.',
                'image_url' => $publicDiskUrl('uploads/slide/seed-slide-3.svg'),
                'button_text' => 'Read Blog',
                'button_url' => url('/blog'),
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Build Faster, Ship Cleaner',
                'subtitle' => 'Web engineering and analytics implementation with practical architecture.',
                'image_url' => $publicDiskUrl('uploads/slide/seed-slide-4.svg'),
                'button_text' => 'Contact Me',
                'button_url' => url('/profile'),
                'display_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Slide::query()->create($slide);
        }
    }

        private function seedBrandImage(string $path, string $startColor, string $endColor, string $label, string $subtitle): void
        {
                if (Storage::disk('public')->exists($path)) {
                        return;
                }

                $svg = $this->buildBrandSvg($startColor, $endColor, $label, $subtitle);
                Storage::disk('public')->put($path, $svg);
        }

        private function buildBrandSvg(string $startColor, string $endColor, string $label, string $subtitle): string
        {
                return <<<SVG
<svg width="1600" height="900" viewBox="0 0 1600 900" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="{$startColor}"/>
            <stop offset="100%" stop-color="{$endColor}"/>
        </linearGradient>
    </defs>
    <rect width="1600" height="900" fill="url(#bg)"/>
    <circle cx="1280" cy="180" r="220" fill="white" fill-opacity="0.08"/>
    <circle cx="340" cy="760" r="260" fill="white" fill-opacity="0.06"/>
    <rect x="140" y="150" width="1320" height="600" rx="34" fill="white" fill-opacity="0.08"/>
    <text x="180" y="430" fill="white" font-size="130" font-family="Segoe UI, Arial, sans-serif" font-weight="700">{$label}</text>
    <text x="180" y="520" fill="white" font-size="46" font-family="Segoe UI, Arial, sans-serif" opacity="0.9">{$subtitle}</text>
</svg>
SVG;
        }
}
