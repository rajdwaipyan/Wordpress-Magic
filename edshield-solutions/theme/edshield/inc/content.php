<?php
/**
 * Company content model.
 *
 * Everything the starter pages say comes from here: services, industries,
 * process, principles and FAQs. The wording describes what ED Shield offers;
 * it states no clients, results, statistics, awards or partnerships. Review it
 * before launch and replace placeholders marked as such.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * The nine service divisions with the copy used by menus, cards and service pages.
 *
 * @return array<string, array<string, mixed>>
 */
function services(): array {
	static $services = null;

	if ( null !== $services ) {
		return $services;
	}

	$services = array(
		'web'        => array(
			'slug'         => 'web-development',
			'title'        => __( 'Website Development', 'edshield' ),
			'division'     => __( 'Digital Experience & Website Development', 'edshield' ),
			'nav'          => __( 'Fast sites that win customers', 'edshield' ),
			'icon'         => 'web',
			'summary'      => __( 'Corporate websites, portals and digital experiences built for speed, clarity and conversion.', 'edshield' ),
			'hero_title'   => __( 'Websites engineered to perform and convert.', 'edshield' ),
			'hero_text'    => __( 'Corporate websites, portals and landing experiences designed around your buyers, built for speed, and simple for your own team to update.', 'edshield' ),
			'tagline'      => __( 'Your website is your hardest-working salesperson.', 'edshield' ),
			'tagline_text' => __( 'It should load fast, explain clearly and turn visits into enquiries, every day, without a developer on call. We design and engineer websites that do exactly that.', 'edshield' ),
			'items'        => array( 'UX and interface design', 'WordPress and headless builds', 'Landing pages', 'Multilingual websites', 'Performance optimisation', 'Accessibility', 'Technical SEO foundations', 'Care and maintenance' ),
			'deliverables' => array(
				array( 'UX & interface design', 'Wireframes and interface design grounded in how your buyers search, compare and decide.', 'layers' ),
				array( 'Website build', 'Custom WordPress themes or headless front ends, engineered for speed and editable without a developer.', 'code' ),
				array( 'Content & search structure', 'Page structure, metadata and schema that help search engines and AI assistants understand you.', 'chart' ),
				array( 'Performance & accessibility', 'Core Web Vitals, image and font optimisation, and accessible markup from the first commit.', 'spark' ),
				array( 'Integrations', 'Forms, CRM, analytics, payments and booking tools connected so every lead lands where it should.', 'crm' ),
				array( 'Care & maintenance', 'Updates, backups, uptime monitoring and steady improvements after launch.', 'shield' ),
			),
			'included'     => array(
				array( 'A design system, not just pages', 'Reusable sections and templates keep every new page consistent with the brand.' ),
				array( 'A site your team can edit', 'Content is managed in a visual editor, so updates do not wait for a developer.' ),
				array( 'Measured performance', 'Speed and accessibility are tested before launch, not promised afterwards.' ),
				array( 'Documentation and handover', 'Your team receives walkthroughs and written guides for everyday changes.' ),
			),
			'faqs'         => array(
				array( 'Which platform do you build on?', 'Most corporate websites are built on WordPress with a custom theme, because it is flexible and easy for teams to edit. When a project needs it, we also build headless or fully custom front ends.' ),
				array( 'Can you redesign our website without losing search visibility?', 'Yes. We map existing URLs, carry over metadata and set up redirects so search visibility is protected during the move.' ),
				array( 'Will we be able to update the website ourselves?', 'Yes. Pages are built from editable sections, and your team is walked through day-to-day updates before handover.' ),
				array( 'Do you handle hosting and maintenance?', 'We can set up and manage hosting, backups, updates and monitoring, or work with your existing provider.' ),
			),
		),
		'commerce'   => array(
			'slug'         => 'ecommerce',
			'title'        => __( 'E-commerce Solutions', 'edshield' ),
			'division'     => __( 'E-commerce Solutions', 'edshield' ),
			'nav'          => __( 'Stores built to sell and scale', 'edshield' ),
			'icon'         => 'cart',
			'summary'      => __( 'Online stores, catalogues and checkout flows engineered to sell and to scale.', 'edshield' ),
			'hero_title'   => __( 'Online stores that sell, scale and run smoothly.', 'edshield' ),
			'hero_text'    => __( 'From catalogue to checkout to fulfilment, we build e-commerce systems that are fast for customers and simple for the teams behind them.', 'edshield' ),
			'tagline'      => __( 'A store is more than a storefront.', 'edshield' ),
			'tagline_text' => __( 'Behind every order are payments, stock, delivery and accounting. We connect the whole chain so growth does not mean more manual work.', 'edshield' ),
			'items'        => array( 'WooCommerce and headless stores', 'Product catalogue and data', 'Checkout and payments', 'Inventory and order sync', 'Shipping and fulfilment', 'Conversion optimisation', 'Marketplace integrations', 'Store analytics' ),
			'deliverables' => array(
				array( 'Store strategy', 'Catalogue structure, customer journeys and the metrics that matter for your category.', 'compass' ),
				array( 'Storefront design', 'Product, category and checkout pages designed for clarity on every screen size.', 'layers' ),
				array( 'Catalogue & product data', 'Clean product data, variations and media, imported and structured for search.', 'chart' ),
				array( 'Payments & checkout', 'Local and international payment gateways integrated into a short, trustworthy checkout.', 'cart' ),
				array( 'Operations integration', 'Inventory, shipping, accounting and ERP connected so orders flow without re-typing.', 'crm' ),
				array( 'Growth & analytics', 'Tracking, reporting and ongoing optimisation based on how customers actually buy.', 'spark' ),
			),
			'included'     => array(
				array( 'A store your team can run', 'Products, prices, promotions and content are managed without technical help.' ),
				array( 'Connected operations', 'Orders, stock and fulfilment stay in sync across the systems you use.' ),
				array( 'A tested checkout', 'Every payment path is tested end to end before customers see it.' ),
				array( 'Room to grow', 'Architecture and hosting sized for more products, traffic and markets.' ),
			),
			'faqs'         => array(
				array( 'Which e-commerce platforms do you work with?', 'We usually build on WooCommerce for flexibility and ownership, and we also work with hosted platforms and headless setups when they fit better.' ),
				array( 'Can you integrate local payment gateways?', 'Yes. We integrate the local and international payment gateways your customers use, subject to the gateway’s own requirements.' ),
				array( 'Can you migrate our existing store?', 'Yes. Products, customers and order history can be migrated, with redirects to protect search visibility.' ),
				array( 'Can the store connect to our inventory or accounting system?', 'Usually, yes. We connect through the system’s API or through an automation platform so stock and orders stay in sync.' ),
			),
		),
		'ai'         => array(
			'slug'         => 'ai-automation',
			'title'        => __( 'AI & Business Automation', 'edshield' ),
			'division'     => __( 'AI & Intelligent Business Automation', 'edshield' ),
			'nav'          => __( 'Workflows that run themselves', 'edshield' ),
			'icon'         => 'ai',
			'summary'      => __( 'AI assistants and automated workflows that take repetitive work off your team.', 'edshield' ),
			'hero_title'   => __( 'Automation that gives your team its time back.', 'edshield' ),
			'hero_text'    => __( 'We map the repetitive steps in your operation, then design automations and AI assistants around them, with people kept in control of the decisions that matter.', 'edshield' ),
			'tagline'      => __( 'Automate the routine. Keep people on the decisions.', 'edshield' ),
			'tagline_text' => __( 'Copying data between systems, chasing follow-ups and assembling reports are jobs for software. Judgement, relationships and approvals stay with your team.', 'edshield' ),
			'items'        => array( 'Process mapping', 'Workflow automation with n8n', 'AI assistants and chatbots', 'Document and data extraction', 'Lead qualification', 'Email and notification flows', 'Reporting automation', 'Human approval steps' ),
			'deliverables' => array(
				array( 'Automation audit', 'A walk through your operation to find the steps that cost the most time and cause the most errors.', 'compass' ),
				array( 'Workflow design', 'Clear diagrams of each automation, its triggers, its data and where people approve.', 'layers' ),
				array( 'AI assistants', 'Assistants that answer, summarise and draft from your own information, inside clear limits.', 'ai' ),
				array( 'Data extraction', 'Documents, emails and forms turned into structured data your systems can use.', 'chart' ),
				array( 'System integrations', 'CRM, email, chat, spreadsheets and internal tools connected through their APIs.', 'crm' ),
				array( 'Monitoring & improvement', 'Logs, alerts and regular reviews so automations stay reliable as your business changes.', 'shield' ),
			),
			'included'     => array(
				array( 'Mapped processes', 'You see how work flows today and exactly what each automation will change.' ),
				array( 'Working automations', 'Automations are built, tested with real cases and documented.' ),
				array( 'People in control', 'Sensitive actions such as sending outreach or approving payments wait for a person.' ),
				array( 'Visible impact', 'Each automation is logged, so you can see the work it handles.' ),
			),
			'faqs'         => array(
				array( 'Which tasks should we automate first?', 'Start with work that is frequent, rule-based and error-prone, such as data entry, notifications and reporting. The automation audit ranks candidates by effort and impact.' ),
				array( 'Will AI replace our team?', 'No. Automation takes over repetitive steps so your team can focus on judgement, customers and decisions. People approve anything sensitive.' ),
				array( 'Which tools do you use?', 'We use workflow platforms such as n8n, the APIs of the systems you already use, and AI models chosen for each task.' ),
				array( 'How is our data protected?', 'Automations use the minimum access they need, credentials are stored securely, and sensitive data stays inside systems you approve.' ),
			),
		),
		'software'   => array(
			'slug'         => 'custom-software',
			'title'        => __( 'Custom Software & Web Apps', 'edshield' ),
			'division'     => __( 'Custom Software & Web Applications', 'edshield' ),
			'nav'          => __( 'Apps, portals and dashboards', 'edshield' ),
			'icon'         => 'code',
			'summary'      => __( 'Internal tools, portals and web applications shaped around how you operate.', 'edshield' ),
			'hero_title'   => __( 'Software shaped around how you actually work.', 'edshield' ),
			'hero_text'    => __( 'Internal tools, customer portals and line-of-business applications, designed with the people who use them and built to be maintained for years.', 'edshield' ),
			'tagline'      => __( 'A website tells people. An application does the work.', 'edshield' ),
			'tagline_text' => __( 'When spreadsheets and off-the-shelf tools stop fitting, a focused application can remove steps, errors and waiting from your whole operation.', 'edshield' ),
			'items'        => array( 'Product discovery', 'UX and UI design', 'Web applications', 'Customer and partner portals', 'Internal tools and dashboards', 'APIs and integrations', 'Testing and QA', 'Support and iteration' ),
			'deliverables' => array(
				array( 'Product discovery', 'Users, workflows, data and constraints mapped before anyone writes code.', 'compass' ),
				array( 'UX & UI design', 'Every screen designed, including empty states, errors and permissions.', 'layers' ),
				array( 'Application build', 'Secure, tested applications with authentication, roles and admin tools.', 'code' ),
				array( 'Dashboards & reporting', 'The numbers your team needs, in one place and up to date.', 'chart' ),
				array( 'APIs & integrations', 'Clean APIs and connections to the systems your application depends on.', 'crm' ),
				array( 'Support & iteration', 'Monitoring, fixes and new features as your needs grow.', 'shield' ),
			),
			'included'     => array(
				array( 'A clear specification', 'Written scope, data model and user flows agreed before build.' ),
				array( 'Working software in stages', 'Short delivery cycles with something real to review each time.' ),
				array( 'Quality built in', 'Automated tests, code review and security checks are part of every release.' ),
				array( 'Handover and documentation', 'Repository, environments and documentation are handed over in full.' ),
			),
			'faqs'         => array(
				array( 'How do you scope a custom build?', 'We start with discovery: users, workflows, data and constraints. The result is a written specification with scope, timeline and cost.' ),
				array( 'Can you extend an existing system?', 'Yes. We review the current code and architecture first, then extend, refactor or integrate depending on what is safest.' ),
				array( 'Which technologies do you use?', 'We choose established, maintainable technologies for each project, typically PHP or JavaScript on the server and modern JavaScript in the browser, with relational databases.' ),
				array( 'What happens after launch?', 'We monitor, fix and keep improving the application through an agreed support plan.' ),
			),
		),
		'crm'        => array(
			'slug'         => 'crm-erp',
			'title'        => __( 'CRM & ERP Integration', 'edshield' ),
			'division'     => __( 'CRM & ERP Integration', 'edshield' ),
			'nav'          => __( 'One source of truth for your business', 'edshield' ),
			'icon'         => 'crm',
			'summary'      => __( 'Connected sales, operations and finance systems with one source of truth.', 'edshield' ),
			'hero_title'   => __( 'One source of truth for sales and operations.', 'edshield' ),
			'hero_text'    => __( 'We implement and connect CRM and ERP systems so leads, customers, orders and invoices move between teams without re-typing or spreadsheets.', 'edshield' ),
			'tagline'      => __( 'Every lead captured. Every hand-off visible.', 'edshield' ),
			'tagline_text' => __( 'When your website, CRM and back office are connected, nothing falls through the gaps, and leadership sees the full pipeline in one place.', 'edshield' ),
			'items'        => array( 'CRM selection and setup', 'GoHighLevel implementation', 'Pipeline and automation design', 'ERP integration', 'Data migration', 'Custom fields and reporting', 'Website and form integration', 'Team training' ),
			'deliverables' => array(
				array( 'Requirements & selection', 'Your sales and operations processes mapped, and the right platform chosen for them.', 'compass' ),
				array( 'CRM implementation', 'Pipelines, stages, fields, permissions and automations configured for your team.', 'crm' ),
				array( 'ERP & finance integration', 'Customers, orders and invoices synchronised with your ERP or accounting system.', 'layers' ),
				array( 'Data migration', 'Contacts, deals and history cleaned, de-duplicated and imported.', 'chart' ),
				array( 'Lead capture', 'Website forms, chat and campaigns feeding the CRM with source tracking.', 'web' ),
				array( 'Training & adoption', 'Role-based training and guides so the system is actually used.', 'users' ),
			),
			'included'     => array(
				array( 'A configured system', 'Your CRM set up around your process, not a generic template.' ),
				array( 'Connected data', 'Website, CRM and back office exchanging data automatically.' ),
				array( 'Reliable reporting', 'Dashboards built on clean data that leadership can trust.' ),
				array( 'A trained team', 'People who know how to use the system from the first day.' ),
			),
			'faqs'         => array(
				array( 'Which CRM platforms do you work with?', 'We work with GoHighLevel and other established CRM platforms, and recommend one only after mapping your process.' ),
				array( 'Can you migrate our existing customer data?', 'Yes. Data is cleaned, de-duplicated and mapped to the new structure, then imported and verified.' ),
				array( 'Can the CRM connect to our website and ERP?', 'Yes. Forms, chat and order data can flow into the CRM, and customer and order data can sync with your ERP or accounting system.' ),
				array( 'How do you make sure the team uses it?', 'Through role-based training, simple guides and a setup that matches how your team already works.' ),
			),
		),
		'security'   => array(
			'slug'         => 'cybersecurity',
			'title'        => __( 'Cybersecurity', 'edshield' ),
			'division'     => __( 'Cybersecurity', 'edshield' ),
			'nav'          => __( 'Protect data, systems and reputation', 'edshield' ),
			'icon'         => 'shield',
			'summary'      => __( 'Assessment, hardening and monitoring that protect your data and your reputation.', 'edshield' ),
			'hero_title'   => __( 'Security that protects what you have built.', 'edshield' ),
			'hero_text'    => __( 'Security reviews, hardening and ongoing monitoring for websites, applications and infrastructure, with clear reporting for leadership.', 'edshield' ),
			'tagline'      => __( 'Security is part of the build, not a patch afterwards.', 'edshield' ),
			'tagline_text' => __( 'Most incidents start with an outdated plugin, a weak password or a forgotten server. We find those gaps, close them and keep watch.', 'edshield' ),
			'items'        => array( 'Security assessments', 'Website and WordPress hardening', 'Vulnerability remediation', 'Access control and MFA', 'Backup and recovery planning', 'Monitoring and alerting', 'Security awareness', 'Incident response support' ),
			'deliverables' => array(
				array( 'Security assessment', 'A review of your websites, applications, accounts and infrastructure, with risks ranked by impact.', 'compass' ),
				array( 'Hardening', 'Configuration, updates and access rules tightened across your systems.', 'shield' ),
				array( 'Vulnerability remediation', 'Identified weaknesses fixed, retested and documented.', 'code' ),
				array( 'Access & identity', 'Multi-factor authentication, least-privilege roles and credential hygiene.', 'lock' ),
				array( 'Backup & recovery', 'Tested backups and a written plan for restoring service quickly.', 'cloud' ),
				array( 'Monitoring', 'Uptime, change and threat monitoring with alerts that reach the right person.', 'spark' ),
			),
			'included'     => array(
				array( 'A clear risk picture', 'A plain-language report of what was found and what it means.' ),
				array( 'Fixes, not just findings', 'Priority issues are remediated and verified, not left in a report.' ),
				array( 'Tested recovery', 'Backups are restored in a test so you know they work.' ),
				array( 'Ongoing watch', 'Monitoring and regular reviews keep protection current.' ),
			),
			'faqs'         => array(
				array( 'Where should we start with security?', 'With an assessment. It shows where you are exposed and which fixes give the most protection for the effort.' ),
				array( 'Can you secure our existing WordPress website?', 'Yes. We update, harden and monitor WordPress sites, remove unused components and set up backups and alerts.' ),
				array( 'What happens if we have already been compromised?', 'We help contain the issue, restore clean systems, close the entry point and document what happened.' ),
				array( 'Do you provide ongoing monitoring?', 'Yes. Monitoring and periodic reviews can continue after the initial work through a support plan.' ),
			),
		),
		'cloud'      => array(
			'slug'         => 'cloud-infrastructure',
			'title'        => __( 'Cloud & Infrastructure', 'edshield' ),
			'division'     => __( 'Cloud & Infrastructure', 'edshield' ),
			'nav'          => __( 'Reliable, cost-aware foundations', 'edshield' ),
			'icon'         => 'cloud',
			'summary'      => __( 'Reliable, cost-aware cloud foundations, migrations and managed environments.', 'edshield' ),
			'hero_title'   => __( 'Infrastructure that stays up and stays affordable.', 'edshield' ),
			'hero_text'    => __( 'Architecture, migration and day-to-day management of the environments your systems run on, sized for the workload and documented for your team.', 'edshield' ),
			'tagline'      => __( 'Fast, stable and boring, in the best way.', 'edshield' ),
			'tagline_text' => __( 'Good infrastructure is invisible. Pages load quickly, backups run, certificates renew and costs match what you actually use.', 'edshield' ),
			'items'        => array( 'Hosting and cloud setup', 'Migrations', 'Performance and caching', 'Backups and disaster recovery', 'Monitoring and uptime', 'Email and DNS configuration', 'Cost optimisation', 'Managed environments' ),
			'deliverables' => array(
				array( 'Architecture', 'Environments designed for your workload, growth and budget.', 'layers' ),
				array( 'Migration', 'Planned moves between hosts or clouds with minimal downtime.', 'cloud' ),
				array( 'Performance', 'Caching, CDN and server tuning for fast, consistent response times.', 'spark' ),
				array( 'Backup & recovery', 'Automated, off-site backups and documented recovery procedures.', 'shield' ),
				array( 'Monitoring', 'Uptime, resource and certificate monitoring with alerting.', 'chart' ),
				array( 'Cost management', 'Regular reviews to remove waste and right-size resources.', 'compass' ),
			),
			'included'     => array(
				array( 'Documented environments', 'Every server, service and credential location is recorded.' ),
				array( 'Staging and production', 'Separate environments so changes are tested before they go live.' ),
				array( 'Recovery you can rely on', 'Backups that are verified and a plan to restore service.' ),
				array( 'Predictable costs', 'Resources matched to real usage and reviewed regularly.' ),
			),
			'faqs'         => array(
				array( 'Can you move our website to better hosting?', 'Yes. We plan the move, test on the new environment and switch over with minimal downtime.' ),
				array( 'Do you manage servers after setup?', 'Yes. Updates, monitoring, backups and incident response can continue through a managed plan.' ),
				array( 'Can you set up a staging environment?', 'Yes. A staging copy lets your team test changes safely before they reach the live site.' ),
				array( 'Can you help reduce our hosting costs?', 'Usually. We review usage and right-size resources, caching and services.' ),
			),
		),
		'industrial' => array(
			'slug'         => 'industrial-digitalization',
			'title'        => __( 'Industrial Digitalization', 'edshield' ),
			'division'     => __( 'Industrial Digitalization', 'edshield' ),
			'nav'          => __( 'Connected operations for industry', 'edshield' ),
			'icon'         => 'factory',
			'summary'      => __( 'Shop-floor data, connected equipment and dashboards for manufacturing and industry.', 'edshield' ),
			'hero_title'   => __( 'A live view of the shop floor.', 'edshield' ),
			'hero_text'    => __( 'Digital tools for production environments: data capture, monitoring and reporting that give managers a current view of what is happening on the floor.', 'edshield' ),
			'tagline'      => __( 'Decisions are only as good as the data behind them.', 'edshield' ),
			'tagline_text' => __( 'Paper logs and end-of-day spreadsheets hide problems until it is too late. Connected data shows them while there is still time to act.', 'edshield' ),
			'items'        => array( 'Operations mapping', 'Production data capture', 'Machine and sensor connectivity', 'Real-time dashboards', 'Maintenance tracking', 'Quality and traceability', 'ERP integration', 'Attendance and workforce tools' ),
			'deliverables' => array(
				array( 'Operations mapping', 'How materials, people and information move through your facility today.', 'compass' ),
				array( 'Data capture', 'Production, downtime and quality data captured at the source.', 'factory' ),
				array( 'Connectivity', 'Machines, sensors and terminals connected to a central system.', 'cloud' ),
				array( 'Dashboards', 'Real-time views for operators, supervisors and leadership.', 'chart' ),
				array( 'Workforce tools', 'Attendance, shifts and task tracking for production teams.', 'users' ),
				array( 'ERP integration', 'Production data flowing into planning, inventory and finance.', 'crm' ),
			),
			'included'     => array(
				array( 'A digital view of operations', 'The data you need, captured once and visible to the right people.' ),
				array( 'Phased rollout', 'Start with one line or process, prove value, then expand.' ),
				array( 'Tools built for the floor', 'Interfaces designed for operators, gloves and noisy environments.' ),
				array( 'Integrated reporting', 'Production data connected to planning and finance.' ),
			),
			'faqs'         => array(
				array( 'Do we need new machines to get started?', 'Usually not. Many improvements come from capturing data that already exists, and older equipment can often be connected through sensors.' ),
				array( 'Can we start small?', 'Yes. We recommend starting with one line or process, proving value and expanding in phases.' ),
				array( 'Can it connect to our ERP?', 'Yes, where the ERP offers an API or import interface. We map the data flows during planning.' ),
				array( 'Do you provide attendance and workforce tools?', 'Yes. We build attendance, shift and task tracking tools for production teams.' ),
			),
		),
		'consulting' => array(
			'slug'         => 'digital-transformation',
			'title'        => __( 'Digital Transformation Consulting', 'edshield' ),
			'division'     => __( 'Digital Transformation Consulting', 'edshield' ),
			'nav'          => __( 'Roadmaps you can act on', 'edshield' ),
			'icon'         => 'compass',
			'summary'      => __( 'Roadmaps, architecture and change support for leaders modernising how they work.', 'edshield' ),
			'hero_title'   => __( 'A transformation plan you can actually deliver.', 'edshield' ),
			'hero_text'    => __( 'An independent view of where technology can move your organisation forward, turned into a sequenced plan with owners, budgets and measurable milestones.', 'edshield' ),
			'tagline'      => __( 'Transformation is a sequence of good decisions.', 'edshield' ),
			'tagline_text' => __( 'Not a single big project. We help leaders decide what to change first, what to leave alone and how to measure progress.', 'edshield' ),
			'items'        => array( 'Digital maturity assessment', 'Technology roadmap', 'Systems architecture', 'Vendor and platform selection', 'Process redesign', 'Change management', 'Business case and budget', 'Programme oversight' ),
			'deliverables' => array(
				array( 'Maturity assessment', 'Where your organisation stands today across people, process, data and technology.', 'compass' ),
				array( 'Roadmap', 'A sequenced plan with priorities, owners, budgets and milestones.', 'flag' ),
				array( 'Architecture', 'How your systems should fit together, now and in three years.', 'layers' ),
				array( 'Platform selection', 'Independent evaluation of vendors and platforms against your needs.', 'chart' ),
				array( 'Change support', 'Communication, training and adoption plans for the people affected.', 'users' ),
				array( 'Programme oversight', 'Regular reviews that keep delivery aligned with the plan.', 'shield' ),
			),
			'included'     => array(
				array( 'An honest baseline', 'A clear view of strengths, gaps and risks.' ),
				array( 'A prioritised roadmap', 'What to do first, what can wait and why.' ),
				array( 'Business cases', 'Costs, benefits and risks for each major initiative.' ),
				array( 'A partner for delivery', 'The same team can help implement the plan.' ),
			),
			'faqs'         => array(
				array( 'Is consulting independent of your other services?', 'Yes. Recommendations are based on your needs. You are free to deliver the plan with us, with others or in-house.' ),
				array( 'How long does an assessment take?', 'It depends on the size of the organisation and the areas in scope. The timeline is agreed in the proposal.' ),
				array( 'Who should be involved from our side?', 'A sponsor from leadership and representatives from the teams most affected by the change.' ),
				array( 'What do we receive at the end?', 'A written assessment, a prioritised roadmap and business cases for the main initiatives.' ),
			),
		),
	);

	/**
	 * Filter the service divisions.
	 *
	 * @param array $services Service definitions keyed by service key.
	 */
	$services = (array) apply_filters( 'edst_services', $services );

	return $services;
}

/**
 * One service by key.
 *
 * @param string $key Service key.
 * @return array<string, mixed>
 */
function service( string $key ): array {
	$all = services();

	return $all[ $key ] ?? array();
}

/**
 * Sectors served, with the copy used on the Industries page.
 *
 * @return array<int, array{0:string,1:string,2:string}> title, text, icon
 */
function industries(): array {
	return array(
		array( __( 'Startups', 'edshield' ), __( 'From idea to launch: a website, product and systems that can grow with the company.', 'edshield' ), 'rocket' ),
		array( __( 'Small & medium businesses', 'edshield' ), __( 'Practical websites, automation and security that save time without enterprise overhead.', 'edshield' ), 'store' ),
		array( __( 'Corporations & enterprise', 'edshield' ), __( 'Integrations, governance and security for organisations with complex systems.', 'edshield' ), 'building' ),
		array( __( 'Manufacturing & industry', 'edshield' ), __( 'Production data, connected equipment and dashboards for the shop floor.', 'edshield' ), 'factory' ),
		array( __( 'Healthcare', 'edshield' ), __( 'Patient-facing websites, booking flows and careful handling of sensitive data.', 'edshield' ), 'health' ),
		array( __( 'Education', 'edshield' ), __( 'Admissions, learning portals and administration tools for schools and institutions.', 'edshield' ), 'education' ),
		array( __( 'NGOs & non-profits', 'edshield' ), __( 'Donor websites, programme reporting and cost-effective systems.', 'edshield' ), 'ngo' ),
		array( __( 'Government & institutions', 'edshield' ), __( 'Accessible public websites, citizen services and secure records.', 'edshield' ), 'government' ),
		array( __( 'Retail & e-commerce', 'edshield' ), __( 'Stores, inventory and fulfilment connected end to end.', 'edshield' ), 'cart' ),
	);
}

/**
 * Delivery stages used on the Process page.
 *
 * @return array<int, array{0:string,1:string,2:string[]}> title, text, tags
 */
function process_stages(): array {
	return array(
		array( __( 'Discover', 'edshield' ), __( 'We learn how your organisation works today: goals, users, systems and constraints. Nothing is designed before the problem is clear.', 'edshield' ), array( 'Stakeholder interviews', 'Systems review', 'Goals and measures' ) ),
		array( __( 'Plan', 'edshield' ), __( 'Findings become a written plan: scope, architecture, timeline and cost, agreed before any build starts.', 'edshield' ), array( 'Written proposal', 'Architecture', 'Timeline' ) ),
		array( __( 'Build', 'edshield' ), __( 'We design and build in short, reviewable increments, with testing and security part of every step.', 'edshield' ), array( 'Design', 'Development', 'QA and security' ) ),
		array( __( 'Launch & improve', 'edshield' ), __( 'We launch carefully, hand over with documentation and training, and keep improving based on real use.', 'edshield' ), array( 'Launch', 'Training', 'Support' ) ),
	);
}

/**
 * The short three-step version used across pages.
 *
 * @return array<int, array{0:string,1:string,2:string}> title, text, icon
 */
function three_steps(): array {
	return array(
		array( __( 'Discovery call', 'edshield' ), __( 'A focused conversation about your goals, systems and constraints.', 'edshield' ), 'call' ),
		array( __( 'Clear proposal', 'edshield' ), __( 'A written scope with timeline, milestones and cost, before work begins.', 'edshield' ), 'calendar' ),
		array( __( 'Build & launch', 'edshield' ), __( 'We deliver in stages, launch, and stay close to improve what comes next.', 'edshield' ), 'launch' ),
	);
}

/**
 * Working principles (About page).
 *
 * @return array<int, array{0:string,1:string}>
 */
function principles(): array {
	return array(
		array( __( 'Think in systems', 'edshield' ), __( 'A website, a CRM and an automation are parts of one system. We design the connections, not just the parts.', 'edshield' ) ),
		array( __( 'Engineer for the long term', 'edshield' ), __( 'Clean code, documentation and sensible technology choices keep what we build maintainable for years.', 'edshield' ) ),
		array( __( 'Be clear and accountable', 'edshield' ), __( 'Written scope, honest timelines and one team responsible from the first call to support.', 'edshield' ) ),
		array( __( 'Keep people in control', 'edshield' ), __( 'Automation and AI handle the routine. Decisions, approvals and relationships stay with people.', 'edshield' ) ),
	);
}

/**
 * Reasons to choose ED Shield (feature cards).
 *
 * @return array<int, array{0:string,1:string,2:string,3:string,4:string}> title, text, art, link label, link
 */
function reasons(): array {
	return array(
		array( __( 'One partner, the full lifecycle', 'edshield' ), __( 'Strategy, design, build, security and support from one accountable team, so nothing gets lost between vendors.', 'edshield' ), 'consulting', __( 'Start a project', 'edshield' ), 'page:contact' ),
		array( __( 'Engineering-grade quality', 'edshield' ), __( 'Tested, documented and maintainable work, built to the same standard whether it is a landing page or a platform.', 'edshield' ), 'software', __( 'See how we work', 'edshield' ), 'page:process' ),
		array( __( 'Security built in', 'edshield' ), __( 'Access control, updates, backups and monitoring are part of every project, not an optional extra.', 'edshield' ), 'security', __( 'Explore cybersecurity', 'edshield' ), 'service:security' ),
	);
}

/**
 * FAQ groups for the FAQ page; the first group is reused on the homepage.
 *
 * @return array<int, array{title:string,items:array<int, array{0:string,1:string}>}>
 */
function faq_groups(): array {
	return array(
		array(
			'title' => __( 'Getting started', 'edshield' ),
			'items' => array(
				array( __( 'What happens on the first call?', 'edshield' ), __( 'We discuss your goals, current systems and constraints. You leave with a clearer view of the options and the next steps, whether or not we work together.', 'edshield' ) ),
				array( __( 'How do we know if ED Shield is the right fit?', 'edshield' ), __( 'We are a good fit when you want one accountable partner for strategy, build and support across web, software, automation and security.', 'edshield' ) ),
				array( __( 'Do you work with organisations outside Bangladesh?', 'edshield' ), __( 'Yes. We work with clients in Bangladesh and internationally, and run projects remotely with regular check-ins.', 'edshield' ) ),
				array( __( 'Can you work alongside our existing team or vendors?', 'edshield' ), __( 'Yes. We can lead a project end to end or work inside your existing team and tools.', 'edshield' ) ),
			),
		),
		array(
			'title' => __( 'Scope & delivery', 'edshield' ),
			'items' => array(
				array( __( 'What does a typical engagement include?', 'edshield' ), __( 'Discovery, a written plan, design, build, testing, launch and handover. The exact scope depends on the service and is agreed before work starts.', 'edshield' ) ),
				array( __( 'How are changes handled during a project?', 'edshield' ), __( 'Changes are discussed openly, assessed for their effect on time and cost, and agreed in writing before they are made.', 'edshield' ) ),
				array( __( 'Can we start with one service and add more later?', 'edshield' ), __( 'Yes. Many engagements start with one priority and expand once the foundation is in place.', 'edshield' ) ),
				array( __( 'What do you need from us to get started?', 'edshield' ), __( 'A main contact, access to the relevant systems, and time for short reviews at each stage.', 'edshield' ) ),
			),
		),
		array(
			'title' => __( 'Pricing & engagement', 'edshield' ),
			'items' => array(
				array( __( 'How do you price projects?', 'edshield' ), __( 'Each engagement is scoped after a discovery conversation. You receive a written proposal with scope, timeline and cost before any work begins.', 'edshield' ) ),
				array( __( 'Do you offer ongoing support?', 'edshield' ), __( 'Yes. Support and improvement can continue after launch through a retainer or a dedicated team.', 'edshield' ) ),
				array( __( 'Which engagement model should we choose?', 'edshield' ), __( 'A project suits a defined outcome, a retainer suits steady improvement, and a dedicated team suits larger programmes. We recommend one after the first call.', 'edshield' ) ),
			),
		),
		array(
			'title' => __( 'Technology & security', 'edshield' ),
			'items' => array(
				array( __( 'Which technologies do you use?', 'edshield' ), __( 'We choose tools to fit the problem: WordPress and modern JavaScript for the web, PHP and APIs for applications, n8n and AI services for automation, and established cloud platforms for hosting.', 'edshield' ) ),
				array( __( 'How do you protect our data?', 'edshield' ), __( 'Access is limited to what the work requires, credentials are never shared in plain text, and sensitive data stays within systems you approve.', 'edshield' ) ),
				array( __( 'Can you integrate with the systems we already use?', 'edshield' ), __( 'Usually, yes. We review your current tools and connect them through their APIs or through an automation platform.', 'edshield' ) ),
			),
		),
		array(
			'title' => __( 'After launch', 'edshield' ),
			'items' => array(
				array( __( 'What happens after launch?', 'edshield' ), __( 'We stay close: monitoring, fixes and improvements continue through an agreed support plan.', 'edshield' ) ),
				array( __( 'Will our team be trained?', 'edshield' ), __( 'Yes. Handover includes walkthroughs and documentation so your team can manage day-to-day changes.', 'edshield' ) ),
			),
		),
	);
}

/**
 * Technologies named in marquee strips. A capability list, not a partnership claim.
 *
 * @return string[]
 */
function technologies(): array {
	return array( 'WordPress', 'Elementor', 'PHP', 'JavaScript', 'REST APIs', 'n8n', 'GoHighLevel', 'MySQL', 'Cloud hosting', 'AI models' );
}

/**
 * Organisation types served (positioning roster).
 *
 * @return string[]
 */
function audiences(): array {
	return array(
		__( 'Startups', 'edshield' ),
		__( 'Small & medium businesses', 'edshield' ),
		__( 'Corporations & enterprise', 'edshield' ),
		__( 'Manufacturing & industry', 'edshield' ),
		__( 'Healthcare organisations', 'edshield' ),
		__( 'Educational institutions', 'edshield' ),
		__( 'NGOs', 'edshield' ),
		__( 'Government & institutions', 'edshield' ),
	);
}
