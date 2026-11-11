<?php
/**
 * Company content for the audience, industry, resource and legal pages, plus
 * the starter articles and placeholder case studies used by the demo import.
 *
 * Only verified company facts appear here. Anything that needs real data
 * (client results, testimonials, legal terms) is marked as a placeholder.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Audience pages (the reference site's "For founders" and "For marketers").
 *
 * @return array<string, array<string, mixed>>
 */
function audience_pages(): array {
	return array(
		'startups' => array(
			'slug'         => 'for-startups',
			'title'        => __( 'For Startups', 'edshield' ),
			'nav'          => __( 'Built for launch speed', 'edshield' ),
			'icon'         => 'rocket',
			'art'          => 'launch',
			'badge'        => __( 'For founders and startups', 'edshield' ),
			'hero_title'   => __( 'You have the vision. We build the <em>foundation.</em>', 'edshield' ),
			'hero_text'    => __( 'Website, product, automation and security from one team, so you can launch quickly without rebuilding everything a year later.', 'edshield' ),
			'tagline'      => __( 'Your launch, handled end to end.', 'edshield' ),
			'tagline_text' => __( 'Early-stage teams lose months coordinating freelancers for every piece of the stack. We plan the website, the first version of the product and the systems behind them together, sized for where you are today and ready for what comes next.', 'edshield' ),
			'groups'       => array(
				array( __( 'Launch website', 'edshield' ), __( 'A fast, credible website with clear messaging, lead capture and analytics from day one.', 'edshield' ), 'web', 'web' ),
				array( __( 'Minimum viable product', 'edshield' ), __( 'A focused first version of your web application, built on code you own and can extend.', 'edshield' ), 'code', 'software' ),
				array( __( 'Automation from the start', 'edshield' ), __( 'Lead routing, onboarding emails and internal workflows that save hours every week.', 'edshield' ), 'ai', 'ai' ),
				array( __( 'Security basics done right', 'edshield' ), __( 'Access control, backups and monitoring set up properly before they become urgent.', 'edshield' ), 'shield', 'security' ),
			),
			'steps'        => array(
				array( __( 'Discovery call', 'edshield' ), __( 'We learn about the idea, the market and the first milestone you need to reach.', 'edshield' ), 'call' ),
				array( __( 'Agree the scope', 'edshield' ), __( 'A written plan with priorities, timeline and cost, sized for an early-stage budget.', 'edshield' ), 'calendar' ),
				array( __( 'Build and launch', 'edshield' ), __( 'We deliver in short stages so you can show progress to customers and investors.', 'edshield' ), 'launch' ),
			),
			'faqs'         => array(
				array( __( 'Can you work with an early-stage budget?', 'edshield' ), __( 'Yes. We scope the first release around the milestone that matters most and plan later stages separately, so you only pay for what you need now.', 'edshield' ) ),
				array( __( 'Do we own the code and accounts?', 'edshield' ), __( 'Yes. Code, domains, hosting and third-party accounts are set up in your name and handed over with documentation.', 'edshield' ) ),
				array( __( 'Can you join after we have started?', 'edshield' ), __( 'Yes. We begin with a short review of what exists, then continue from there rather than starting over by default.', 'edshield' ) ),
				array( __( 'Do you help after launch?', 'edshield' ), __( 'Yes. Support, improvements and new features can continue through a retainer as the company grows.', 'edshield' ) ),
			),
		),
		'growth'   => array(
			'slug'         => 'for-growing-businesses',
			'title'        => __( 'For Growing Businesses', 'edshield' ),
			'nav'          => __( 'Systems that scale with you', 'edshield' ),
			'icon'         => 'chart',
			'art'          => 'software',
			'badge'        => __( 'For SMEs and growing teams', 'edshield' ),
			'hero_title'   => __( 'Systems that keep up with your <em>growth.</em>', 'edshield' ),
			'hero_text'    => __( 'When spreadsheets, disconnected tools and manual work start slowing the team down, we connect and automate the systems you depend on.', 'edshield' ),
			'tagline'      => __( 'Growth should not mean more manual work.', 'edshield' ),
			'tagline_text' => __( 'Growing businesses often run on tools chosen one at a time. We map how work actually flows, then connect your website, CRM, finance and operations so information is entered once and reaches everyone who needs it.', 'edshield' ),
			'groups'       => array(
				array( __( 'CRM and sales pipeline', 'edshield' ), __( 'Leads, deals and customer history in one place, connected to your website and email.', 'edshield' ), 'crm', 'crm' ),
				array( __( 'Workflow automation', 'edshield' ), __( 'Approvals, reminders, reports and data entry handled by reliable automations.', 'edshield' ), 'ai', 'ai' ),
				array( __( 'Online sales and e-commerce', 'edshield' ), __( 'Stores, payments and inventory that work together instead of in separate tabs.', 'edshield' ), 'cart', 'commerce' ),
				array( __( 'Security and cloud', 'edshield' ), __( 'Protected accounts, reliable backups and hosting that grows with demand.', 'edshield' ), 'cloud', 'cloud' ),
			),
			'steps'        => array(
				array( __( 'Map the workflow', 'edshield' ), __( 'We review how sales, operations and finance work today and where time is lost.', 'edshield' ), 'call' ),
				array( __( 'Prioritise', 'edshield' ), __( 'A clear plan that starts with the change that saves the most time or risk.', 'edshield' ), 'calendar' ),
				array( __( 'Connect and improve', 'edshield' ), __( 'We build, train your team and keep improving as the business grows.', 'edshield' ), 'launch' ),
			),
			'faqs'         => array(
				array( __( 'Do we have to replace our current tools?', 'edshield' ), __( 'Not by default. We start with what you have and replace a tool only when connecting it would cost more than changing it.', 'edshield' ) ),
				array( __( 'How do you keep work running during changes?', 'edshield' ), __( 'Changes are introduced in stages, tested alongside the current process, and switched over only when your team is ready.', 'edshield' ) ),
				array( __( 'Can you train our staff?', 'edshield' ), __( 'Yes. Every handover includes walkthroughs and written guides for everyday tasks.', 'edshield' ) ),
				array( __( 'Can you support us monthly?', 'edshield' ), __( 'Yes. A growth retainer covers improvements, monitoring and support each month.', 'edshield' ) ),
			),
		),
	);
}

/**
 * Industry pages (the reference site's industry detail pages).
 *
 * @return array<string, array<string, mixed>>
 */
function industry_pages(): array {
	return array(
		'manufacturing' => array(
			'slug'         => 'manufacturing',
			'title'        => __( 'Manufacturing & Industry', 'edshield' ),
			'nav'          => __( 'Connected operations and data', 'edshield' ),
			'icon'         => 'factory',
			'art'          => 'industrial',
			'hero_title'   => __( 'Connected operations for <em>manufacturing.</em>', 'edshield' ),
			'hero_text'    => __( 'Production data, maintenance, quality and inventory brought together in dashboards and workflows your teams can trust.', 'edshield' ),
			'tagline'      => __( 'The shop floor already produces data. Most of it is lost.', 'edshield' ),
			'tagline_text' => __( 'Machine counts, downtime notes and quality checks often live on paper or in separate files. We capture that information once and turn it into clear, shared views of what is happening on each line.', 'edshield' ),
			'challenges'   => array(
				array( __( 'Data on paper and in spreadsheets', 'edshield' ), __( 'Shift reports and checklists are hard to search, compare or act on.', 'edshield' ) ),
				array( __( 'Late visibility of problems', 'edshield' ), __( 'Downtime and quality issues surface hours or days after they start.', 'edshield' ) ),
				array( __( 'Systems that do not talk', 'edshield' ), __( 'Planning, inventory and finance tools hold different versions of the truth.', 'edshield' ) ),
				array( __( 'Security of connected equipment', 'edshield' ), __( 'More connected devices mean more entry points that need protection.', 'edshield' ) ),
			),
			'solutions'    => array(
				array( __( 'Production dashboards', 'edshield' ), __( 'Output, downtime and quality on one screen, per line and per shift.', 'edshield' ), 'chart' ),
				array( __( 'Digital checklists and forms', 'edshield' ), __( 'Inspections and handovers captured on tablets instead of paper.', 'edshield' ), 'check' ),
				array( __( 'Maintenance workflows', 'edshield' ), __( 'Requests, schedules and history tracked from report to repair.', 'edshield' ), 'layers' ),
				array( __( 'ERP and inventory integration', 'edshield' ), __( 'Stock and orders kept in step with what the floor actually produces.', 'edshield' ), 'crm' ),
				array( __( 'Automated reporting', 'edshield' ), __( 'Daily and weekly reports generated and sent without manual work.', 'edshield' ), 'ai' ),
				array( __( 'Network and device security', 'edshield' ), __( 'Segmented networks, access control and monitoring for connected equipment.', 'edshield' ), 'shield' ),
			),
			'services'     => array( 'industrial', 'software', 'crm', 'security' ),
			'faqs'         => array(
				array( __( 'Do we need new machines to collect data?', 'edshield' ), __( 'Usually not. We start with the data you can already capture and add sensors or connections only where they bring clear value.', 'edshield' ) ),
				array( __( 'Can you work with our existing ERP?', 'edshield' ), __( 'In most cases, yes. We review the available interfaces first and recommend the simplest reliable way to connect.', 'edshield' ) ),
				array( __( 'Will this disrupt production?', 'edshield' ), __( 'Changes are planned around shifts and introduced line by line, with the current process kept as a fallback.', 'edshield' ) ),
				array( __( 'Who maintains the system afterwards?', 'edshield' ), __( 'Your team receives training and documentation, and we can continue with support and improvements.', 'edshield' ) ),
			),
		),
		'healthcare'    => array(
			'slug'         => 'healthcare',
			'title'        => __( 'Healthcare', 'edshield' ),
			'nav'          => __( 'Patient-friendly and secure', 'edshield' ),
			'icon'         => 'health',
			'art'          => 'calendar',
			'hero_title'   => __( 'Digital services patients find <em>easy.</em>', 'edshield' ),
			'hero_text'    => __( 'Websites, booking flows and internal systems for clinics, hospitals and health organisations, built with careful handling of sensitive data.', 'edshield' ),
			'tagline'      => __( 'Every phone call that could have been a booking costs time.', 'edshield' ),
			'tagline_text' => __( 'Patients expect to find information, book and get reminders online. Staff need fewer manual steps. We design both sides together, with privacy and security considered from the start.', 'edshield' ),
			'challenges'   => array(
				array( __( 'Phone-only booking', 'edshield' ), __( 'Front-desk teams spend hours on calls that a simple online flow could handle.', 'edshield' ) ),
				array( __( 'Information that is hard to find', 'edshield' ), __( 'Patients cannot quickly see services, doctors, hours or preparation steps.', 'edshield' ) ),
				array( __( 'Manual follow-ups', 'edshield' ), __( 'Reminders and post-visit messages depend on someone remembering to send them.', 'edshield' ) ),
				array( __( 'Sensitive data', 'edshield' ), __( 'Patient information needs strict access control and careful storage.', 'edshield' ) ),
			),
			'solutions'    => array(
				array( __( 'Accessible healthcare websites', 'edshield' ), __( 'Clear service, doctor and location pages that work on every device.', 'edshield' ), 'web' ),
				array( __( 'Online booking flows', 'edshield' ), __( 'Appointment requests and scheduling connected to your team’s calendar.', 'edshield' ), 'calendar' ),
				array( __( 'Automated reminders', 'edshield' ), __( 'Confirmations and reminders by email or message, sent automatically.', 'edshield' ), 'ai' ),
				array( __( 'Patient enquiries in one place', 'edshield' ), __( 'Messages from the website routed to the right team with a clear history.', 'edshield' ), 'crm' ),
				array( __( 'Internal tools', 'edshield' ), __( 'Simple portals for staff schedules, inventory and reporting.', 'edshield' ), 'code' ),
				array( __( 'Privacy and security', 'edshield' ), __( 'Access control, encryption and backups designed around sensitive data.', 'edshield' ), 'lock' ),
			),
			'services'     => array( 'web', 'ai', 'software', 'security' ),
			'faqs'         => array(
				array( __( 'Can online booking connect to our current system?', 'edshield' ), __( 'Often, yes. We review your scheduling system first and connect directly where it allows, or use a simple request flow where it does not.', 'edshield' ) ),
				array( __( 'How do you handle patient data?', 'edshield' ), __( 'We collect only what is needed, restrict access by role, encrypt data in transit and agree retention rules with you before launch.', 'edshield' ) ),
				array( __( 'Can staff update the website themselves?', 'edshield' ), __( 'Yes. Doctors, services and notices are managed in a visual editor without technical help.', 'edshield' ) ),
				array( __( 'Do you support multiple locations?', 'edshield' ), __( 'Yes. Locations, hours and services can be managed per branch.', 'edshield' ) ),
			),
		),
		'education'     => array(
			'slug'         => 'education',
			'title'        => __( 'Education', 'edshield' ),
			'nav'          => __( 'Admissions, portals and admin', 'edshield' ),
			'icon'         => 'education',
			'art'          => 'web',
			'hero_title'   => __( 'Technology that lets educators <em>teach.</em>', 'edshield' ),
			'hero_text'    => __( 'Websites, admissions, learning portals and administration tools for schools, colleges, training centres and institutions.', 'edshield' ),
			'tagline'      => __( 'Admissions season should not overwhelm the office.', 'edshield' ),
			'tagline_text' => __( 'Enquiries, applications, fees and results often move through email, paper and spreadsheets. We give students, parents and staff clear online paths and give administrators one reliable record.', 'edshield' ),
			'challenges'   => array(
				array( __( 'Paper-based admissions', 'edshield' ), __( 'Applications arrive in many formats and are hard to track and follow up.', 'edshield' ) ),
				array( __( 'Scattered communication', 'edshield' ), __( 'Notices and updates reach parents and students through too many channels.', 'edshield' ) ),
				array( __( 'Manual administration', 'edshield' ), __( 'Attendance, fees and reports take staff time every week.', 'edshield' ) ),
				array( __( 'Outdated websites', 'edshield' ), __( 'Prospective students cannot find programmes, fees or deadlines easily.', 'edshield' ) ),
			),
			'solutions'    => array(
				array( __( 'Institution websites', 'edshield' ), __( 'Programme, admissions and news pages your staff can update.', 'edshield' ), 'web' ),
				array( __( 'Online admissions', 'edshield' ), __( 'Application forms, document uploads and status tracking in one flow.', 'edshield' ), 'check' ),
				array( __( 'Student and parent portals', 'edshield' ), __( 'Results, notices, schedules and fees in a secure login area.', 'edshield' ), 'users' ),
				array( __( 'Administration tools', 'edshield' ), __( 'Attendance, fee tracking and reporting built around how you work.', 'edshield' ), 'code' ),
				array( __( 'Automated communication', 'edshield' ), __( 'Reminders and announcements sent to the right groups automatically.', 'edshield' ), 'mail' ),
				array( __( 'Data protection', 'edshield' ), __( 'Role-based access and backups for student records.', 'edshield' ), 'shield' ),
			),
			'services'     => array( 'web', 'software', 'ai', 'security' ),
			'faqs'         => array(
				array( __( 'Can we start with admissions only?', 'edshield' ), __( 'Yes. Many institutions start with online admissions and add portals and administration tools later.', 'edshield' ) ),
				array( __( 'Can parents and students use phones?', 'edshield' ), __( 'Yes. Every page and portal is designed for mobile first.', 'edshield' ) ),
				array( __( 'Can you migrate our existing records?', 'edshield' ), __( 'Yes. We plan migrations carefully, test with copies of the data and keep the old records until the new system is confirmed.', 'edshield' ) ),
				array( __( 'Who will manage the system?', 'edshield' ), __( 'Your staff, after training. We provide documentation and ongoing support if needed.', 'edshield' ) ),
			),
		),
		'ngo'           => array(
			'slug'         => 'ngos-non-profits',
			'title'        => __( 'NGOs & Non-profits', 'edshield' ),
			'nav'          => __( 'Programmes, donors and reporting', 'edshield' ),
			'icon'         => 'ngo',
			'art'          => 'consulting',
			'hero_title'   => __( 'More time for the mission, less for <em>admin.</em>', 'edshield' ),
			'hero_text'    => __( 'Websites, programme reporting and cost-effective systems for NGOs, foundations and development organisations.', 'edshield' ),
			'tagline'      => __( 'Donors and partners expect clear, timely reporting.', 'edshield' ),
			'tagline_text' => __( 'Field data, beneficiary records and reports often pass through many hands before they are ready. We build simple tools that collect information once and turn it into the reports your partners need.', 'edshield' ),
			'challenges'   => array(
				array( __( 'Reporting takes weeks', 'edshield' ), __( 'Data from field teams has to be collected, cleaned and combined by hand.', 'edshield' ) ),
				array( __( 'Limited budgets', 'edshield' ), __( 'Systems must be affordable to run and simple to maintain.', 'edshield' ) ),
				array( __( 'Communicating impact', 'edshield' ), __( 'Websites rarely show programmes and results in a compelling way.', 'edshield' ) ),
				array( __( 'Protecting beneficiary data', 'edshield' ), __( 'Personal information must be handled responsibly and securely.', 'edshield' ) ),
			),
			'solutions'    => array(
				array( __( 'Programme websites', 'edshield' ), __( 'Clear stories, programme pages and ways for supporters to get involved.', 'edshield' ), 'web' ),
				array( __( 'Field data collection', 'edshield' ), __( 'Mobile-friendly forms that feed one central database.', 'edshield' ), 'check' ),
				array( __( 'Reporting dashboards', 'edshield' ), __( 'Indicators and progress available to teams and partners at any time.', 'edshield' ), 'chart' ),
				array( __( 'Donor and contact management', 'edshield' ), __( 'Supporters, partners and communications kept in one CRM.', 'edshield' ), 'crm' ),
				array( __( 'Workflow automation', 'edshield' ), __( 'Routine reports and reminders produced automatically.', 'edshield' ), 'ai' ),
				array( __( 'Responsible data handling', 'edshield' ), __( 'Access control, consent records and secure backups.', 'edshield' ), 'lock' ),
			),
			'services'     => array( 'web', 'crm', 'ai', 'consulting' ),
			'faqs'         => array(
				array( __( 'Do you work with donor-funded budgets?', 'edshield' ), __( 'Yes. We scope work to fit grant periods and reporting requirements, and keep running costs predictable.', 'edshield' ) ),
				array( __( 'Can field teams work offline?', 'edshield' ), __( 'Where needed, we choose tools that collect data offline and synchronise when a connection is available.', 'edshield' ) ),
				array( __( 'Can partners see results directly?', 'edshield' ), __( 'Yes. Dashboards can be shared with partners with access limited to what they need.', 'edshield' ) ),
				array( __( 'Can you help with our website content?', 'edshield' ), __( 'Yes. We help structure programme and impact pages and train your team to keep them current.', 'edshield' ) ),
			),
		),
		'government'    => array(
			'slug'         => 'government-institutions',
			'title'        => __( 'Government & Institutions', 'edshield' ),
			'nav'          => __( 'Accessible, secure public services', 'edshield' ),
			'icon'         => 'government',
			'art'          => 'system',
			'hero_title'   => __( 'Public services people can <em>rely on.</em>', 'edshield' ),
			'hero_text'    => __( 'Accessible websites, citizen services and secure record systems for public bodies and institutions.', 'edshield' ),
			'tagline'      => __( 'Public information should be easy to find and safe to use.', 'edshield' ),
			'tagline_text' => __( 'Citizens expect clear information and simple online services. Institutions need security, accountability and systems their staff can maintain. We design for both, with accessibility and security built in.', 'edshield' ),
			'challenges'   => array(
				array( __( 'Hard-to-navigate websites', 'edshield' ), __( 'Important information is buried, outdated or difficult to use on mobile.', 'edshield' ) ),
				array( __( 'Paper-based services', 'edshield' ), __( 'Applications and requests require visits, forms and manual processing.', 'edshield' ) ),
				array( __( 'Security expectations', 'edshield' ), __( 'Public systems are frequent targets and must be protected carefully.', 'edshield' ) ),
				array( __( 'Accessibility requirements', 'edshield' ), __( 'Services must work for everyone, including people using assistive technology.', 'edshield' ) ),
			),
			'solutions'    => array(
				array( __( 'Accessible public websites', 'edshield' ), __( 'Clear structure, plain language and accessible design across devices.', 'edshield' ), 'web' ),
				array( __( 'Online services and forms', 'edshield' ), __( 'Applications and requests submitted and tracked online.', 'edshield' ), 'check' ),
				array( __( 'Records and case management', 'edshield' ), __( 'Secure systems for files, cases and approvals.', 'edshield' ), 'layers' ),
				array( __( 'Security assessment', 'edshield' ), __( 'Reviews, hardening and monitoring of public-facing systems.', 'edshield' ), 'shield' ),
				array( __( 'Cloud and hosting', 'edshield' ), __( 'Reliable hosting with backups and recovery plans.', 'edshield' ), 'cloud' ),
				array( __( 'Digital transformation planning', 'edshield' ), __( 'Roadmaps that move services online in manageable stages.', 'edshield' ), 'compass' ),
			),
			'services'     => array( 'web', 'security', 'cloud', 'consulting' ),
			'faqs'         => array(
				array( __( 'Do you build to accessibility standards?', 'edshield' ), __( 'Yes. We design and test against current web accessibility guidelines and fix issues before launch.', 'edshield' ) ),
				array( __( 'Can you work within procurement processes?', 'edshield' ), __( 'Yes. We provide written proposals, scopes and documentation suitable for formal procurement.', 'edshield' ) ),
				array( __( 'Where is data hosted?', 'edshield' ), __( 'Hosting location and providers are agreed with you to meet your policies and regulations.', 'edshield' ) ),
				array( __( 'Can you assess an existing system?', 'edshield' ), __( 'Yes. A security and accessibility review is often the best first step.', 'edshield' ) ),
			),
		),
		'retail'        => array(
			'slug'         => 'retail-ecommerce',
			'title'        => __( 'Retail & E-commerce', 'edshield' ),
			'nav'          => __( 'Stores connected end to end', 'edshield' ),
			'icon'         => 'cart',
			'art'          => 'commerce',
			'hero_title'   => __( 'Sell online without the manual <em>work.</em>', 'edshield' ),
			'hero_text'    => __( 'Online stores, inventory and fulfilment connected end to end, for retailers and brands of every size.', 'edshield' ),
			'tagline'      => __( 'Every order should flow through without retyping.', 'edshield' ),
			'tagline_text' => __( 'Orders, stock, payments and delivery often live in different systems. We connect them so an order placed online reaches the warehouse, the accounts and the customer updates automatically.', 'edshield' ),
			'challenges'   => array(
				array( __( 'Stock out of sync', 'edshield' ), __( 'Shop, marketplace and warehouse numbers disagree, causing overselling.', 'edshield' ) ),
				array( __( 'Slow checkout', 'edshield' ), __( 'Complicated or slow checkouts lose customers at the last step.', 'edshield' ) ),
				array( __( 'Manual order handling', 'edshield' ), __( 'Orders are copied between systems by hand, creating delays and errors.', 'edshield' ) ),
				array( __( 'Payment and data security', 'edshield' ), __( 'Stores handle personal and payment data that must be protected.', 'edshield' ) ),
			),
			'solutions'    => array(
				array( __( 'Online stores', 'edshield' ), __( 'Fast, mobile-friendly stores with clear product pages and checkout.', 'edshield' ), 'cart' ),
				array( __( 'Inventory synchronisation', 'edshield' ), __( 'Stock kept consistent across the store, marketplaces and warehouse.', 'edshield' ), 'layers' ),
				array( __( 'Order automation', 'edshield' ), __( 'Orders routed to fulfilment, accounting and customer updates automatically.', 'edshield' ), 'ai' ),
				array( __( 'Customer data and CRM', 'edshield' ), __( 'Purchase history and marketing lists kept in one place.', 'edshield' ), 'crm' ),
				array( __( 'Sales reporting', 'edshield' ), __( 'Clear daily views of sales, margins and stock levels.', 'edshield' ), 'chart' ),
				array( __( 'Store security', 'edshield' ), __( 'Updates, monitoring and hardening for your store and payment flow.', 'edshield' ), 'shield' ),
			),
			'services'     => array( 'commerce', 'crm', 'ai', 'security' ),
			'faqs'         => array(
				array( __( 'Can you move our existing store?', 'edshield' ), __( 'Yes. We plan the migration of products, customers and orders and protect search rankings with redirects.', 'edshield' ) ),
				array( __( 'Do you connect marketplaces?', 'edshield' ), __( 'Where the marketplace provides an interface, we connect stock and orders to your store and back office.', 'edshield' ) ),
				array( __( 'Which payment providers do you support?', 'edshield' ), __( 'We integrate the providers available in your market and recommend options during discovery.', 'edshield' ) ),
				array( __( 'Can our team manage products?', 'edshield' ), __( 'Yes. Products, prices and promotions are managed in an easy dashboard.', 'edshield' ) ),
			),
		),
	);
}

/**
 * What the free website audit covers.
 *
 * @return array<int, array{0:string,1:string,2:string}> title, text, icon
 */
function audit_checks(): array {
	return array(
		array( __( 'Speed and Core Web Vitals', 'edshield' ), __( 'How fast your pages load on mobile and desktop, and what slows them down.', 'edshield' ), 'chart' ),
		array( __( 'Security basics', 'edshield' ), __( 'Outdated software, exposed logins, missing backups and other common risks.', 'edshield' ), 'shield' ),
		array( __( 'Search foundations', 'edshield' ), __( 'Titles, structure, indexing and technical issues that affect how you are found.', 'edshield' ), 'compass' ),
		array( __( 'Accessibility', 'edshield' ), __( 'Contrast, keyboard use and structure that decide whether everyone can use the site.', 'edshield' ), 'users' ),
		array( __( 'Conversion paths', 'edshield' ), __( 'How easily a visitor can understand your offer and contact you.', 'edshield' ), 'arrow-up-right' ),
		array( __( 'Integrations and tracking', 'edshield' ), __( 'Whether forms, analytics and your CRM receive what they should.', 'edshield' ), 'crm' ),
	);
}

/**
 * Technology groups for the technology page.
 *
 * @return array<int, array{title:string,text:string,tags:string}>
 */
function tech_groups(): array {
	return array(
		array(
			'title' => __( 'WordPress and Elementor', 'edshield' ),
			'text'  => __( 'For corporate websites your team can edit visually. Custom themes keep pages fast and consistent; Elementor gives editors freedom without breaking the design.', 'edshield' ),
			'tags'  => 'WordPress, Elementor, PHP',
		),
		array(
			'title' => __( 'Custom web applications', 'edshield' ),
			'text'  => __( 'Portals, dashboards and internal tools built on proven open technologies, with code and data you own.', 'edshield' ),
			'tags'  => 'PHP, JavaScript, REST APIs, MySQL',
		),
		array(
			'title' => __( 'Automation and AI', 'edshield' ),
			'text'  => __( 'Workflows that connect your tools and AI models that draft, classify and summarise, always with a person approving what matters.', 'edshield' ),
			'tags'  => 'n8n, AI models, REST APIs',
		),
		array(
			'title' => __( 'CRM and sales systems', 'edshield' ),
			'text'  => __( 'Pipelines, contact records and follow-ups connected to your website and messaging.', 'edshield' ),
			'tags'  => 'GoHighLevel, CRM integrations',
		),
		array(
			'title' => __( 'Hosting, security and data', 'edshield' ),
			'text'  => __( 'Reliable cloud hosting, backups, monitoring and hardening for everything we build.', 'edshield' ),
			'tags'  => 'Cloud hosting, MySQL, backups, monitoring',
		),
	);
}

/**
 * Privacy policy text (a structure to complete, not legal advice).
 */
function privacy_text(): string {
	$sections = array(
		__( 'Who we are', 'edshield' )                 => __( 'This website is operated by ED Shield Solutions. [Add the registered company name, address and contact email.]', 'edshield' ),
		__( 'What we collect', 'edshield' )            => __( 'When you send a message through our forms we receive the details you enter, such as your name, email address, organisation, phone number and message. Our servers also record technical information such as IP addresses for security.', 'edshield' ),
		__( 'How we use it', 'edshield' )              => __( 'We use your details to reply to your enquiry, to provide the services you request and to keep our website secure. [List any other purposes, such as newsletters, only if they apply.]', 'edshield' ),
		__( 'Sharing', 'edshield' )                    => __( 'We do not sell personal information. We share it only with service providers that help us run the website and our business, under agreements that protect it. [List providers, for example hosting and email.]', 'edshield' ),
		__( 'How long we keep it', 'edshield' )        => __( '[State how long enquiries and client records are kept.]', 'edshield' ),
		__( 'Your rights', 'edshield' )                => __( 'You can ask to see, correct or delete the personal information we hold about you by contacting us. [Add the contact address and any rights under the laws that apply to you.]', 'edshield' ),
		__( 'Cookies', 'edshield' )                    => __( 'This website uses only the cookies needed for it to work. [Update this section if analytics or marketing tools are added.]', 'edshield' ),
	);

	$html = '<p><strong>' . esc_html__( 'Template to complete: replace every part in square brackets and have the final text reviewed before publishing.', 'edshield' ) . '</strong></p>';

	foreach ( $sections as $title => $text ) {
		$html .= '<h2>' . esc_html( $title ) . '</h2><p>' . esc_html( $text ) . '</p>';
	}

	return $html;
}

/**
 * Terms of service text (a structure to complete, not legal advice).
 */
function terms_text(): string {
	$sections = array(
		__( 'About these terms', 'edshield' )        => __( 'These terms apply to the use of this website. Project work is governed by the written proposal and agreement signed for each engagement.', 'edshield' ),
		__( 'Use of the website', 'edshield' )       => __( 'You may browse and share the content of this website for personal and business information. Do not misuse the website or attempt to access it in unauthorised ways.', 'edshield' ),
		__( 'Content and ownership', 'edshield' )    => __( 'The text, design and code of this website belong to ED Shield Solutions unless stated otherwise. [Add any third-party credits.]', 'edshield' ),
		__( 'No professional advice', 'edshield' )   => __( 'Articles on this website are general information and not advice for your specific situation.', 'edshield' ),
		__( 'Liability', 'edshield' )                => __( '[Add the limitation of liability that applies under your governing law.]', 'edshield' ),
		__( 'Governing law', 'edshield' )            => __( '[State the governing law and jurisdiction.]', 'edshield' ),
		__( 'Contact', 'edshield' )                  => __( 'Questions about these terms can be sent through our contact page.', 'edshield' ),
	);

	$html = '<p><strong>' . esc_html__( 'Template to complete: replace every part in square brackets and have the final text reviewed before publishing.', 'edshield' ) . '</strong></p>';

	foreach ( $sections as $title => $text ) {
		$html .= '<h2>' . esc_html( $title ) . '</h2><p>' . esc_html( $text ) . '</p>';
	}

	return $html;
}

/**
 * Starter articles imported with the demo. General guidance only: no client
 * names, results or statistics.
 *
 * @return array<int, array{slug:string,title:string,excerpt:string,category:string,art:string,content:string}>
 */
function demo_posts(): array {
	$p = static function ( array $blocks ): string {
		$out = '';

		foreach ( $blocks as $block ) {
			if ( 'h' === $block[0] ) {
				$out .= '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html( $block[1] ) . '</h2><!-- /wp:heading -->' . "\n";
			} elseif ( 'l' === $block[0] ) {
				$out .= '<!-- wp:list --><ul class="wp-block-list">';

				foreach ( $block[1] as $item ) {
					$out .= '<!-- wp:list-item --><li>' . esc_html( $item ) . '</li><!-- /wp:list-item -->';
				}

				$out .= '</ul><!-- /wp:list -->' . "\n";
			} else {
				$out .= '<!-- wp:paragraph --><p>' . esc_html( $block[1] ) . '</p><!-- /wp:paragraph -->' . "\n";
			}
		}

		return $out;
	};

	return array(
		array(
			'slug'     => 'automation-for-growing-teams-where-to-start',
			'title'    => __( 'Automation for growing teams: where to start', 'edshield' ),
			'excerpt'  => __( 'Automate the repetitive, rule-based work first and keep people in charge of judgement. A practical way to choose your first workflows.', 'edshield' ),
			'category' => __( 'Automation', 'edshield' ),
			'art'      => 'ai',
			'content'  => $p(
				array(
					array( 'p', __( 'Most teams know they should automate more, but few know where to begin. The best first candidates are not the most impressive ones. They are the tasks that happen often, follow clear rules and cause real frustration when they are forgotten.', 'edshield' ) ),
					array( 'h', __( 'Start with a simple inventory', 'edshield' ) ),
					array( 'p', __( 'For one week, ask each team to note the tasks they repeat: copying details from a form into a spreadsheet, sending the same reminder, preparing the same weekly report. You will quickly see patterns.', 'edshield' ) ),
					array( 'h', __( 'Good first candidates', 'edshield' ) ),
					array( 'l', array( __( 'Routing new enquiries to the right person with all the details attached', 'edshield' ), __( 'Sending confirmations, reminders and follow-ups', 'edshield' ), __( 'Moving data between your website, CRM and finance tools', 'edshield' ), __( 'Producing recurring reports from data you already collect', 'edshield' ) ) ),
					array( 'h', __( 'What to keep human', 'edshield' ) ),
					array( 'p', __( 'Decisions that affect customers, money or reputation should stay with people. AI can draft a reply, summarise a request or suggest a category, but a person should approve anything that leaves the building.', 'edshield' ) ),
					array( 'h', __( 'Measure before and after', 'edshield' ) ),
					array( 'p', __( 'Write down how long a task takes today and how often it goes wrong. After the automation is live, check again. Clear before-and-after numbers make it easy to decide what to automate next.', 'edshield' ) ),
				)
			),
		),
		array(
			'slug'     => 'website-security-checklist-for-growing-businesses',
			'title'    => __( 'A website security checklist for growing businesses', 'edshield' ),
			'excerpt'  => __( 'Most website incidents start with something simple: an outdated plugin, a shared password or a missing backup. Ten checks to do this month.', 'edshield' ),
			'category' => __( 'Security', 'edshield' ),
			'art'      => 'security',
			'content'  => $p(
				array(
					array( 'p', __( 'Website security rarely fails because of sophisticated attacks. It usually fails because of small things left undone. This checklist covers the basics every organisation should have in place.', 'edshield' ) ),
					array( 'h', __( 'Access', 'edshield' ) ),
					array( 'l', array( __( 'Give every person their own login, never a shared account', 'edshield' ), __( 'Turn on two-factor authentication for administrators', 'edshield' ), __( 'Remove accounts for people who have left', 'edshield' ) ) ),
					array( 'h', __( 'Software', 'edshield' ) ),
					array( 'l', array( __( 'Keep the platform, themes and plugins up to date', 'edshield' ), __( 'Delete plugins and themes you no longer use', 'edshield' ), __( 'Install software only from trusted sources', 'edshield' ) ) ),
					array( 'h', __( 'Recovery', 'edshield' ) ),
					array( 'l', array( __( 'Take automatic daily backups stored away from the website', 'edshield' ), __( 'Test a restore at least once', 'edshield' ), __( 'Write down who to call and what to do if the site goes down', 'edshield' ) ) ),
					array( 'h', __( 'Monitoring', 'edshield' ) ),
					array( 'p', __( 'Set up uptime monitoring and alerts for unexpected changes. The sooner you know about a problem, the smaller it stays.', 'edshield' ) ),
				)
			),
		),
		array(
			'slug'     => 'website-or-web-application-what-to-build',
			'title'    => __( 'Website or web application? How to decide what to build', 'edshield' ),
			'excerpt'  => __( 'A website tells people about you. An application does work for them. Knowing the difference saves budget and months of rework.', 'edshield' ),
			'category' => __( 'Web development', 'edshield' ),
			'art'      => 'software',
			'content'  => $p(
				array(
					array( 'p', __( 'Many projects start with the request for "a website" when the real need is an application, or the other way round. The difference matters because they are planned, built and maintained differently.', 'edshield' ) ),
					array( 'h', __( 'You probably need a website if', 'edshield' ) ),
					array( 'l', array( __( 'The main goal is to explain what you do and generate enquiries', 'edshield' ), __( 'Most visitors read, compare and contact you', 'edshield' ), __( 'Your team needs to update pages and articles regularly', 'edshield' ) ) ),
					array( 'h', __( 'You probably need an application if', 'edshield' ) ),
					array( 'l', array( __( 'Users log in to do something: book, apply, order, track or report', 'edshield' ), __( 'The system stores records and applies business rules', 'edshield' ), __( 'Different people need different permissions', 'edshield' ) ) ),
					array( 'h', __( 'Often the answer is both', 'edshield' ) ),
					array( 'p', __( 'A common setup is a marketing website for visitors and a separate application for customers or staff, connected by a shared login or data. Planning both together avoids duplicate work and keeps the experience consistent.', 'edshield' ) ),
				)
			),
		),
		array(
			'slug'     => 'five-decisions-before-moving-to-a-new-crm',
			'title'    => __( 'Five decisions to make before moving to a new CRM', 'edshield' ),
			'excerpt'  => __( 'A CRM migration succeeds or fails on decisions made before any data moves. Settle these five first.', 'edshield' ),
			'category' => __( 'CRM', 'edshield' ),
			'art'      => 'crm',
			'content'  => $p(
				array(
					array( 'p', __( 'Changing CRM is a good moment to fix how sales and service work, not just where the data lives. These five decisions shape everything that follows.', 'edshield' ) ),
					array( 'h', __( '1. What is a lead, and when does it become a deal?', 'edshield' ) ),
					array( 'p', __( 'Agree the stages of your pipeline and the exact moment a record moves between them. Everything else, from reports to automations, depends on this.', 'edshield' ) ),
					array( 'h', __( '2. Which data really needs to move?', 'edshield' ) ),
					array( 'p', __( 'Old, duplicate and incomplete records make a new system feel broken from day one. Clean and select before you migrate.', 'edshield' ) ),
					array( 'h', __( '3. Which tools must connect?', 'edshield' ) ),
					array( 'p', __( 'List the website forms, email, phone, finance and messaging tools that should create or update records automatically.', 'edshield' ) ),
					array( 'h', __( '4. Who owns the CRM?', 'edshield' ) ),
					array( 'p', __( 'Name a person responsible for fields, stages and user access. Without an owner, every CRM slowly fills with workarounds.', 'edshield' ) ),
					array( 'h', __( '5. How will you know it worked?', 'edshield' ) ),
					array( 'p', __( 'Choose two or three measures, such as response time to new enquiries, and check them before and after the move.', 'edshield' ) ),
				)
			),
		),
	);
}

/**
 * Placeholder case studies imported with the demo. Every one is marked as a
 * placeholder and must be replaced with a real, approved project.
 *
 * @return array<int, array{title:string,industry:string,services:string,art:string}>
 */
function demo_projects(): array {
	return array(
		array( 'title' => __( 'Placeholder case study: corporate website and CRM', 'edshield' ), 'industry' => __( 'Corporate', 'edshield' ), 'services' => 'Website Development, CRM & ERP Integration', 'art' => 'web' ),
		array( 'title' => __( 'Placeholder case study: patient booking automation', 'edshield' ), 'industry' => __( 'Healthcare', 'edshield' ), 'services' => 'AI & Business Automation, Cybersecurity', 'art' => 'calendar' ),
		array( 'title' => __( 'Placeholder case study: production dashboard', 'edshield' ), 'industry' => __( 'Manufacturing', 'edshield' ), 'services' => 'Industrial Digitalization, Custom Software', 'art' => 'industrial' ),
		array( 'title' => __( 'Placeholder case study: online admissions portal', 'edshield' ), 'industry' => __( 'Education', 'edshield' ), 'services' => 'Custom Software, Website Development', 'art' => 'software' ),
		array( 'title' => __( 'Placeholder case study: programme reporting dashboard', 'edshield' ), 'industry' => __( 'NGO', 'edshield' ), 'services' => 'Custom Software, AI & Business Automation', 'art' => 'consulting' ),
		array( 'title' => __( 'Placeholder case study: store security and inventory sync', 'edshield' ), 'industry' => __( 'Retail', 'edshield' ), 'services' => 'E-commerce Solutions, Cybersecurity', 'art' => 'commerce' ),
	);
}
