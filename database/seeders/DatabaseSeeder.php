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

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->updateOrCreate(
            ['role' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'rahmatfauji@gmail.com',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
            ]
        );

        Profile::query()->updateOrCreate(
            ['email' => 'rahmat@example.com'],
            [
                'full_name' => 'Rahmat Fauji',
                'title' => 'Web Developer & Content Creator',
            'bio' => 'I focus on building modern web applications that are fast, clean, and easy to use. I enjoy combining elegant design with efficient technical solutions.',
                'phone' => '+62 812-0000-0000',
                'location' => 'Indonesia',
                'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde',
            ]
        );

        $blogPosts = [
            [
                'slug' => 'power-bi-executive-dashboard-design-framework',
                'title' => 'Power BI Executive Dashboard Design Framework',
                'excerpt' => 'A practical framework to design decision-ready dashboards for executives.',
                'content' => 'The strongest executive dashboards combine KPI hierarchy, context-rich trends, and clear drill paths. Start with strategic metrics, then add operational drivers so leaders can move from signal to action in minutes.',
                'featured_image' => 'https://images.unsplash.com/photo-1551281044-8b5bd6f1f3ff',
                'published_at' => Carbon::now()->subDays(12),
                'is_published' => true,
            ],
            [
                'slug' => 'power-query-patterns-for-messy-business-data',
                'title' => 'Power Query Patterns for Messy Business Data',
                'excerpt' => 'Reusable Power Query techniques for inconsistent files and manual reports.',
                'content' => 'Most analytics delays come from unstable source data. Build repeatable Power Query steps for schema validation, type enforcement, and null handling to create robust datasets that refresh reliably.',
                'featured_image' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4',
                'published_at' => Carbon::now()->subDays(11),
                'is_published' => true,
            ],
            [
                'slug' => 'dax-measures-that-improve-kpi-accuracy',
                'title' => 'DAX Measures That Improve KPI Accuracy',
                'excerpt' => 'Essential DAX patterns to avoid misleading KPI calculations.',
                'content' => 'KPI trust depends on calculation quality. Use explicit filter context, robust time-intelligence patterns, and separate base vs display measures to keep KPIs stable across visual interactions.',
                'featured_image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6',
                'published_at' => Carbon::now()->subDays(10),
                'is_published' => true,
            ],
            [
                'slug' => 'building-row-level-security-in-power-bi',
                'title' => 'Building Row-Level Security in Power BI',
                'excerpt' => 'How to implement scalable row-level security for multi-team reporting.',
                'content' => 'RLS should protect data without hurting usability. Map user identities to business entities, validate role behavior with test personas, and document exceptions to keep governance transparent.',
                'featured_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40',
                'published_at' => Carbon::now()->subDays(9),
                'is_published' => true,
            ],
            [
                'slug' => 'power-bi-performance-tuning-checklist',
                'title' => 'Power BI Performance Tuning Checklist',
                'excerpt' => 'A checklist to reduce slow visuals and long refresh times.',
                'content' => 'Performance tuning starts with model design. Reduce cardinality, optimize relationships, disable unnecessary interactions, and use aggregations to keep dashboards responsive for end users.',
                'featured_image' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a',
                'published_at' => Carbon::now()->subDays(8),
                'is_published' => true,
            ],
            [
                'slug' => 'automating-monthly-reporting-with-power-automate',
                'title' => 'Automating Monthly Reporting with Power Automate',
                'excerpt' => 'Cut manual reporting cycles with flow-based automation.',
                'content' => 'Power Automate can orchestrate data extraction, approvals, and stakeholder notifications. Define trigger boundaries and retry logic carefully to avoid silent failures in critical workflows.',
                'featured_image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3',
                'published_at' => Carbon::now()->subDays(7),
                'is_published' => true,
            ],
            [
                'slug' => 'power-apps-for-data-collection-at-scale',
                'title' => 'Power Apps for Data Collection at Scale',
                'excerpt' => 'Designing Power Apps forms that stay reliable across teams and regions.',
                'content' => 'Successful data-collection apps prioritize validation, offline handling, and clean submission tracking. Standardize field logic early so downstream analytics remains consistent and audit-friendly.',
                'featured_image' => 'https://images.unsplash.com/photo-1504639725590-34d0984388bd',
                'published_at' => Carbon::now()->subDays(6),
                'is_published' => true,
            ],
            [
                'slug' => 'from-excel-to-governed-power-bi-semantic-model',
                'title' => 'From Excel to a Governed Power BI Semantic Model',
                'excerpt' => 'A transition strategy from spreadsheet chaos to governed analytics.',
                'content' => 'Move critical logic from spreadsheets into a shared semantic model. Define naming conventions, reusable measures, and ownership boundaries so business users can self-serve with confidence.',
                'featured_image' => 'https://images.unsplash.com/photo-1485217988980-11786ced9454',
                'published_at' => Carbon::now()->subDays(5),
                'is_published' => true,
            ],
            [
                'slug' => 'kpi-storytelling-techniques-for-power-bi',
                'title' => 'KPI Storytelling Techniques for Power BI',
                'excerpt' => 'Turn raw KPI visuals into clear narratives for business stakeholders.',
                'content' => 'Good storytelling in BI means sequencing visuals by business questions. Lead with performance status, explain drivers, and close with actions so decision-makers know exactly what to do next.',
                'featured_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f',
                'published_at' => Carbon::now()->subDays(4),
                'is_published' => true,
            ],
            [
                'slug' => 'designing-finance-kpi-suite-in-power-bi',
                'title' => 'Designing a Finance KPI Suite in Power BI',
                'excerpt' => 'Best practices for revenue, cost, and margin analytics in one model.',
                'content' => 'Finance reporting works best with aligned dimensions and controlled time logic. Build standardized gross margin, variance, and forecast measures to avoid conflicting numbers across teams.',
                'featured_image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f',
                'published_at' => Carbon::now()->subDays(3),
                'is_published' => true,
            ],
            [
                'slug' => 'power-apps-and-power-bi-integration-playbook',
                'title' => 'Power Apps and Power BI Integration Playbook',
                'excerpt' => 'How to connect analytics and operational actions in one experience.',
                'content' => 'Embedding Power Apps in Power BI closes the loop between insight and action. Use role-aware app screens and prefilled context from reports to speed up issue resolution.',
                'featured_image' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984',
                'published_at' => Carbon::now()->subDays(2),
                'is_published' => true,
            ],
            [
                'slug' => 'power-bi-governance-model-for-growing-teams',
                'title' => 'A Power BI Governance Model for Growing Teams',
                'excerpt' => 'A lightweight governance model that scales without slowing delivery.',
                'content' => 'Define workspace standards, deployment flow, certification labels, and ownership maps. Governance should accelerate quality and trust, not create unnecessary reporting bottlenecks.',
                'featured_image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085',
                'published_at' => Carbon::now()->subDay(),
                'is_published' => true,
            ],
        ];

        BlogPost::query()->delete();

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
                'image_url' => 'https://images.unsplash.com/photo-1551281044-8b5bd6f1f3ff',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Finance KPI Suite for CFO Office',
                'category' => 'Power BI Dashboard',
                'summary' => 'A full finance dashboard suite covering revenue, OPEX, EBITDA, and variance analysis.',
                'description' => 'Implemented standardized DAX measures and month-end reconciliation views to align finance reporting across planning, accounting, and leadership teams.',
                'project_url' => 'https://example.com/powerbi-finance-kpi-suite',
                'image_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Inventory Health & Forecast Dashboard',
                'category' => 'Power BI Analytics',
                'summary' => 'Monitors stock aging, stockout risk, and demand forecasts in one operational view.',
                'description' => 'Combined ERP data and forecast assumptions to surface reorder priorities and reduce excess stock while maintaining service levels.',
                'project_url' => 'https://example.com/powerbi-inventory-health',
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'HR Workforce Analytics Dashboard',
                'category' => 'People Analytics',
                'summary' => 'Tracks hiring funnel, turnover, and workforce composition for HR strategy.',
                'description' => 'Designed role-based views for HRBP, recruiters, and leadership with reliable monthly snapshots and trend metrics.',
                'project_url' => 'https://example.com/powerbi-hr-workforce-analytics',
                'image_url' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Power Apps Field Audit Solution',
                'category' => 'Power Apps',
                'summary' => 'Mobile app for field teams to submit structured audit findings in real time.',
                'description' => 'Built validation rules, photo capture, and offline-friendly forms. Data feeds directly into Power BI for compliance and quality tracking.',
                'project_url' => 'https://example.com/powerapps-field-audit',
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Procurement Request App with Approval Flow',
                'category' => 'Power Apps + Power Automate',
                'summary' => 'End-to-end procurement request app with approval routing and SLA visibility.',
                'description' => 'Integrated Power Apps forms, approval workflows, and Power BI monitoring to reduce cycle time and improve procurement governance.',
                'project_url' => 'https://example.com/powerapps-procurement-flow',
                'image_url' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d',
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Customer Segmentation Intelligence Model',
                'category' => 'Data Analytics',
                'summary' => 'Segmented customer value and behavior patterns to support targeted campaigns.',
                'description' => 'Delivered segment-level dashboards with churn risk indicators and conversion insights used by marketing and sales operations.',
                'project_url' => 'https://example.com/customer-segmentation-intelligence',
                'image_url' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984',
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Retail Store Performance Cockpit',
                'category' => 'Power BI Dashboard',
                'summary' => 'Store-level sales and conversion cockpit for area managers.',
                'description' => 'Introduced benchmark views and region comparisons to identify low-performing stores and prioritize coaching actions.',
                'project_url' => 'https://example.com/retail-performance-cockpit',
                'image_url' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a',
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'title' => 'Project Portfolio Tracking Dashboard',
                'category' => 'PMO Analytics',
                'summary' => 'Portfolio dashboard for milestone risk, budget utilization, and delivery confidence.',
                'description' => 'Modeled project timelines and financial data to provide a unified PMO view for steering committee decision-making.',
                'project_url' => 'https://example.com/project-portfolio-tracking',
                'image_url' => 'https://images.unsplash.com/photo-1485217988980-11786ced9454',
                'display_order' => 9,
                'is_active' => true,
            ],
            [
                'title' => 'Service Desk SLA Monitoring',
                'category' => 'Operational Analytics',
                'summary' => 'SLA and ticket flow analytics for IT service management teams.',
                'description' => 'Implemented queue aging, breach prediction, and workload balancing visuals to improve response time and customer satisfaction.',
                'project_url' => 'https://example.com/service-desk-sla-monitoring',
                'image_url' => 'https://images.unsplash.com/photo-1518770660439-4636190af475',
                'display_order' => 10,
                'is_active' => true,
            ],
            [
                'title' => 'Power Apps Incident Reporting Hub',
                'category' => 'Power Apps',
                'summary' => 'Incident intake app with standardized root-cause and corrective-action capture.',
                'description' => 'Created a structured intake and escalation process with analytics-ready data model for weekly incident review dashboards.',
                'project_url' => 'https://example.com/powerapps-incident-hub',
                'image_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085',
                'display_order' => 11,
                'is_active' => true,
            ],
            [
                'title' => 'Power BI Governance & Adoption Program',
                'category' => 'BI Strategy',
                'summary' => 'Governance blueprint with workspace standards, certification flow, and adoption metrics.',
                'description' => 'Developed a scalable governance operating model and measured adoption through active user, report quality, and business outcome indicators.',
                'project_url' => 'https://example.com/powerbi-governance-adoption',
                'image_url' => 'https://images.unsplash.com/photo-1484417894907-623942c8ee29',
                'display_order' => 12,
                'is_active' => true,
            ],
        ];

        PortfolioItem::query()->delete();

        foreach ($portfolioItems as $item) {
            PortfolioItem::query()->create($item);
        }

        $slides = [
            [
                'title' => 'Data Analytics for Better Decisions',
                'subtitle' => 'Turn raw data into clear insights that accelerate business decisions.',
                'image_url' => 'https://images.unsplash.com/photo-1551281044-8b5bd6f1f3ff',
                'button_text' => 'Explore Dashboard',
                'button_url' => route('portfolio.index'),
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Real-Time KPI Monitoring',
                'subtitle' => 'Monitor team and product performance in real time through clear data visualizations.',
                'image_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c',
                'button_text' => 'View Portfolio',
                'button_url' => route('portfolio.index'),
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Predictive Insights & Growth',
                'subtitle' => 'Use historical trends to predict new opportunities and strengthen growth strategy.',
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f',
                'button_text' => 'Read Blog',
                'button_url' => route('blog.index'),
                'display_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Slide::query()->updateOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }
    }
}
