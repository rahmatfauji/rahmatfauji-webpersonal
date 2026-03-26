<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class BlogPostMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $publicDiskUrl = static fn (string $path): string => Storage::disk('public')->url($path);

        $posts = [
            [
                'slug' => 'power-bi-executive-dashboard-design-framework',
                'title' => 'Power BI Executive Dashboard Design Framework',
                'category' => 'Power BI Strategy',
                'tags' => ['Power BI', 'Dashboard Design', 'Executive Reporting'],
                'excerpt' => 'A practical framework to design decision-ready dashboards for executives.',
                'content' => '<p>Executive dashboards are useful only when they compress complexity into fast decisions. The goal is not to show more charts, but to expose the few signals leaders actually need.</p><h2>Start With Decision Paths</h2><p>Map the decisions an executive needs to make weekly or monthly, then build the KPI layer around those decisions. This prevents visual noise and keeps every page tied to action.</p><h3>Use a KPI Hierarchy</h3><p>Lead with top-line outcomes, then connect them to operational drivers such as revenue mix, conversion, cost movement, and regional contribution. Executives need summary first and explanation second.</p><h2>Design for Fast Context</h2><p>Pair every KPI with trend direction, target comparison, and drill paths. A dashboard should answer what changed, why it changed, and where to look next.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-1.svg'),
                'published_at' => Carbon::create(2026, 3, 14, 9, 0, 0),
                'is_published' => true,
                'view_count' => 184,
            ],
            [
                'slug' => 'power-query-patterns-for-messy-business-data',
                'title' => 'Power Query Patterns for Messy Business Data',
                'category' => 'Data Preparation',
                'tags' => ['Power Query', 'ETL', 'Data Cleaning'],
                'excerpt' => 'Reusable Power Query techniques for inconsistent files and manual reports.',
                'content' => '<p>Messy source files are one of the biggest reasons analytics pipelines fail. A stable transformation layer in Power Query protects reporting from recurring source variation.</p><h2>Stabilize Schema First</h2><p>Validate column presence, data types, and naming patterns before doing any business logic. If the schema is unstable, every downstream calculation becomes fragile.</p><h3>Build Reusable Cleanup Steps</h3><p>Standardize trimming, null replacement, date parsing, and duplicate handling into reusable step sequences. This reduces maintenance and makes refresh failures easier to debug.</p><h2>Design for Auditability</h2><p>Keep transformation logic readable and grouped by intent so future changes are safe. Clean code in Power Query matters as much as clean code in application development.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-2.svg'),
                'published_at' => Carbon::create(2026, 3, 16, 9, 0, 0),
                'is_published' => true,
                'view_count' => 143,
            ],
            [
                'slug' => 'dax-measures-that-improve-kpi-accuracy',
                'title' => 'DAX Measures That Improve KPI Accuracy',
                'category' => 'DAX & Modeling',
                'tags' => ['DAX', 'KPI', 'Semantic Model'],
                'excerpt' => 'Essential DAX patterns to avoid misleading KPI calculations.',
                'content' => '<p>KPI accuracy depends on how well measures respect filter context and business rules. Small measure shortcuts often create large trust issues later.</p><h2>Separate Base and Display Measures</h2><p>Create a clear base-measure layer for raw calculations, then add display measures for formatting and business presentation. This keeps logic consistent across visuals.</p><h3>Control Filter Context Explicitly</h3><p>Functions such as CALCULATE, ALL, and KEEPFILTERS should be used intentionally. Measures that rely on accidental context are difficult to verify and often fail in drill-through scenarios.</p><h2>Test Across Views</h2><p>Validate measures in summary cards, matrix visuals, and filtered slices. A KPI is only correct if it remains correct everywhere it is consumed.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-3.svg'),
                'published_at' => Carbon::create(2026, 3, 18, 9, 0, 0),
                'is_published' => true,
                'view_count' => 126,
            ],
            [
                'slug' => 'building-row-level-security-in-power-bi',
                'title' => 'Building Row-Level Security in Power BI',
                'category' => 'Governance',
                'tags' => ['Power BI', 'Security', 'Governance'],
                'excerpt' => 'How to implement scalable row-level security for multi-team reporting.',
                'content' => '<p>Row-level security should protect data without making reporting difficult to maintain. The best implementations are simple, testable, and mapped directly to business entities.</p><h2>Model Access Around Business Rules</h2><p>Map users to teams, entities, or regions using a dedicated access table. This avoids hard-coded role logic and keeps security rules aligned with organizational structure.</p><h3>Test With Realistic Personas</h3><p>Create test accounts for each access scenario and verify edge cases such as regional leads, shared service teams, and admin exceptions. RLS failures are often hidden until real users hit them.</p><h2>Document Every Exception</h2><p>Security models become risky when exceptions live only in developer memory. Make ownership and exception logic visible so future changes remain safe.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-4.svg'),
                'published_at' => Carbon::create(2026, 3, 19, 9, 0, 0),
                'is_published' => true,
                'view_count' => 119,
            ],
            [
                'slug' => 'power-bi-performance-tuning-checklist',
                'title' => 'Power BI Performance Tuning Checklist',
                'category' => 'Performance Optimization',
                'tags' => ['Power BI', 'Performance', 'Modeling'],
                'excerpt' => 'A checklist to reduce slow visuals and long refresh times.',
                'content' => '<p>Performance issues usually come from model design decisions made early and left unreviewed. A checklist-based approach helps teams tune systematically instead of guessing.</p><h2>Reduce Model Friction</h2><p>Start by checking cardinality, relationship direction, unnecessary columns, and hidden measures that are no longer used. Leaner models nearly always perform better.</p><h3>Measure the Heavy Visuals</h3><p>Use performance analyzer to isolate expensive visuals and DAX queries. Fixing the slowest 20 percent of report interactions often improves the whole user experience.</p><h2>Optimize for Refresh and Use</h2><p>Good BI performance covers both user interactions and dataset refresh cycles. Evaluate both paths before declaring the model healthy.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-5.svg'),
                'published_at' => Carbon::create(2026, 3, 20, 9, 0, 0),
                'is_published' => true,
                'view_count' => 111,
            ],
            [
                'slug' => 'automating-monthly-reporting-with-power-automate',
                'title' => 'Automating Monthly Reporting with Power Automate',
                'category' => 'Automation',
                'tags' => ['Power Automate', 'Reporting Automation', 'Workflow'],
                'excerpt' => 'Cut manual reporting cycles with flow-based automation.',
                'content' => '<p>Monthly reporting often breaks because ownership is fragmented across extraction, review, and distribution. Automation works best when the full process is mapped first.</p><h2>Automate the Critical Path</h2><p>Focus on the steps that cause delay or human error, such as file collection, approval reminders, and stakeholder notifications. The goal is reliable flow, not automation for its own sake.</p><h3>Define Retry and Escalation Rules</h3><p>Every automated process needs fallback behavior. Explicit retry limits and escalation messages prevent silent failures that are hard to spot during busy reporting periods.</p><h2>Keep Manual Overrides Visible</h2><p>Not every exception should be automated. Preserve an auditable override path for finance, operations, or executive reviews when judgement is still required.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-6.svg'),
                'published_at' => Carbon::create(2026, 3, 21, 9, 0, 0),
                'is_published' => true,
                'view_count' => 94,
            ],
            [
                'slug' => 'power-apps-for-data-collection-at-scale',
                'title' => 'Power Apps for Data Collection at Scale',
                'category' => 'Apps & Forms',
                'tags' => ['Power Apps', 'Forms', 'Operations'],
                'excerpt' => 'Designing Power Apps forms that stay reliable across teams and regions.',
                'content' => '<p>Data collection apps succeed when they make clean data entry easier than bad habits. Form design, validation, and submission feedback matter as much as backend storage.</p><h2>Design for Real Usage</h2><p>Map who enters data, where they work, and what device they use. Field staff, managers, and shared service teams usually need different interaction patterns.</p><h3>Validate Before Submission</h3><p>Use clear field rules, constrained choices, and visible error states to prevent downstream data repair. Analytics quality improves when validation happens at the point of entry.</p><h2>Track Submission Confidence</h2><p>Users need confirmation that their entries were accepted. Reliable success states and retry handling reduce duplicate submissions and support issues.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-7.svg'),
                'published_at' => Carbon::create(2026, 3, 22, 9, 0, 0),
                'is_published' => true,
                'view_count' => 88,
            ],
            [
                'slug' => 'from-excel-to-governed-power-bi-semantic-model',
                'title' => 'From Excel to a Governed Power BI Semantic Model',
                'category' => 'Data Governance',
                'tags' => ['Power BI', 'Governance', 'Semantic Model'],
                'excerpt' => 'A transition strategy from spreadsheet chaos to governed analytics.',
                'content' => '<p>Spreadsheet-driven reporting can move quickly at first, but it becomes brittle as teams grow. A governed semantic model creates a stable center for logic and ownership.</p><h2>Extract Shared Definitions</h2><p>Start by identifying duplicated KPI logic and shared dimensions across workbooks. Those repeated pieces are the best candidates for centralization.</p><h3>Assign Ownership Early</h3><p>Governance fails when everyone assumes someone else owns the model. Define who approves measure changes, data source onboarding, and release cadence from the beginning.</p><h2>Protect Self-Service With Standards</h2><p>Good governance should increase delivery speed by making trusted building blocks easy to reuse. Standard naming and certified datasets are the foundation.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-8.svg'),
                'published_at' => Carbon::create(2026, 3, 23, 9, 0, 0),
                'is_published' => true,
                'view_count' => 76,
            ],
            [
                'slug' => 'kpi-storytelling-techniques-for-power-bi',
                'title' => 'KPI Storytelling Techniques for Power BI',
                'category' => 'Dashboard Design',
                'tags' => ['Power BI', 'Storytelling', 'KPI'],
                'excerpt' => 'Turn raw KPI visuals into clear narratives for business stakeholders.',
                'content' => '<p>Dashboards become more effective when they are structured as narratives instead of disconnected pages. Storytelling helps stakeholders move from interpretation to action faster.</p><h2>Sequence by Business Questions</h2><p>Begin with current performance, then explain the drivers, then direct attention to the actions required. This sequence mirrors how stakeholders naturally process information.</p><h3>Use Contrast Sparingly</h3><p>Highlight only the visuals that require attention. Overusing color and emphasis weakens the message and makes it harder to identify true exceptions.</p><h2>End With an Action Layer</h2><p>Every KPI story should close with what the reader can do next. Dashboards are more useful when they actively reduce decision friction.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-9.svg'),
                'published_at' => Carbon::create(2026, 3, 24, 9, 0, 0),
                'is_published' => true,
                'view_count' => 69,
            ],
            [
                'slug' => 'power-bi-governance-model-for-growing-teams',
                'title' => 'A Power BI Governance Model for Growing Teams',
                'category' => 'Governance',
                'tags' => ['Governance', 'Power BI', 'Operating Model'],
                'excerpt' => 'A lightweight governance model that scales without slowing delivery.',
                'content' => '<p>Governance becomes valuable when it improves trust without creating unnecessary process weight. Growing teams need standards that are strong enough to guide delivery but light enough to keep momentum.</p><h2>Define the Operating Model</h2><p>Clarify workspace ownership, deployment flow, support boundaries, and certification criteria. Teams work faster when expectations are explicit.</p><h3>Review Risk by Layer</h3><p>Separate model governance, report design standards, and access control rules. Different risks need different controls, and mixing them creates confusion.</p><h2>Scale With Reusable Rules</h2><p>Templates, naming conventions, and release checklists create consistency without constant meetings. Good governance is operational, not rhetorical.</p>',
                'featured_image' => $publicDiskUrl('uploads/blog/seed-blog-10.svg'),
                'published_at' => Carbon::create(2026, 3, 25, 9, 0, 0),
                'is_published' => true,
                'view_count' => 61,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }
}