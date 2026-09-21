<?php

namespace Database\Seeders;

use App\Models\HelpArticle;
use Illuminate\Database\Seeder;

class HelpArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            // ALL ROLES
            [
                'slug'=>'getting-started', 'roles'=>['all'], 'category'=>'Getting Started',
                'title'=>'Getting Started with ANI-CARE',
                'summary'=>'Basic steps for logging in, opening your dashboard, and using the Help Center.',
                'content'=>"1. Log in using your ANI-CARE username/email and password.\n2. After login, the system redirects you to the dashboard assigned to your role.\n3. Use the dashboard cards or navigation buttons to open Marketplace, Orders, Milling, Profile, Notifications, Reports, or other available modules.\n4. Your account role controls which modules and actions are available to you.\n5. If you are unsure what to do, click the green ? button or open Help Center and ask a question.",
                'keywords'=>['login','dashboard','start','help','role'], 'route_name'=>'dashboard.redirect', 'sort_order'=>10,
            ],
            [
                'slug'=>'notifications', 'roles'=>['all'], 'category'=>'General',
                'title'=>'How to Check Notifications',
                'summary'=>'View transaction and system alerts from the notification bell or Notifications page.',
                'content'=>"1. Click the notification bell in the dashboard interface when available.\n2. Open the Notifications page to see your alerts.\n3. Select an alert to open its related transaction or module when a destination is available.\n4. Use Read All when you want to clear unread notification indicators.\nNotifications can include order, milling, approval, and other system updates.",
                'keywords'=>['notification','bell','alert','unread'], 'route_name'=>'notifications.index', 'sort_order'=>20,
            ],
            [
                'slug'=>'profile', 'roles'=>['all'], 'category'=>'Account',
                'title'=>'How to Update Your Profile',
                'summary'=>'Keep your account and role-specific profile information updated.',
                'content'=>"Open your Profile module from your dashboard. Review the information shown for your account and save any allowed changes.\n\nFarmers can also maintain farm-related information, while Millers and Residents have their respective profile fields. Keep contact and location information accurate because some transactions use this information.",
                'keywords'=>['profile','account','edit','update','location'], 'sort_order'=>30,
            ],

            // ADMIN
            [
                'slug'=>'admin-dashboard', 'roles'=>['admin'], 'category'=>'Admin',
                'title'=>'Admin Dashboard Overview', 'summary'=>'Understand the main Admin modules and what each one is used for.',
                'content'=>"The Admin dashboard is the central monitoring and management area of ANI-CARE.\n\nUse Farmers & Millers to review registered farmer and miller accounts.\nUse Inventory to monitor purchased rice/palay stock.\nUse Distribution to manage distribution schedules and completion.\nUse Marketplace to monitor or perform admin marketplace transactions.\nUse Orders to review product purchase transactions.\nUse Milling Requests to monitor admin milling transactions.\nUse Approvals to approve or revoke registered accounts.\nUse Announcements to publish system updates.\nUse Reports to review transaction records.\nUse Central VAT Setting to configure the system VAT setting.\nUse Notifications to review system alerts.",
                'keywords'=>['admin','dashboard','modules','overview'], 'route_name'=>'admin.dashboard', 'sort_order'=>100,
            ],
            [
                'slug'=>'admin-approve-users', 'roles'=>['admin'], 'category'=>'Admin',
                'title'=>'How to Approve a Farmer or Miller Account', 'summary'=>'Review pending registrations and approve or revoke access.',
                'content'=>"1. Open User Approvals from the Admin dashboard.\n2. Review the applicant information.\n3. For an eligible pending account, use Approve.\n4. If access needs to be removed later, use Revoke where available.\n5. Approved users can then use the modules assigned to their role.",
                'keywords'=>['approval','approve','farmer','miller','registration','revoke'], 'route_name'=>'admin.approvals', 'sort_order'=>110,
            ],
            [
                'slug'=>'admin-orders', 'roles'=>['admin'], 'category'=>'Admin',
                'title'=>'How Admin Monitors Product Orders', 'summary'=>'Review orders, order details, receipt confirmation, and invoices.',
                'content'=>"1. Open Orders from the Admin dashboard.\n2. Select an order to view its detailed information.\n3. Review buyer, farmer/seller, product, quantity, amount, payment, delivery, and status information available on the order.\n4. Use the available order action for the current workflow stage.\n5. Open the invoice when you need a receipt-style transaction record or download a PDF when available.",
                'keywords'=>['admin','orders','order','invoice','buyer','seller','payment'], 'route_name'=>'admin.orders.index', 'sort_order'=>120,
            ],
            [
                'slug'=>'admin-reports', 'roles'=>['admin'], 'category'=>'Reports',
                'title'=>'How to Use Transaction Reports', 'summary'=>'Filter and review product purchases and farmer/miller milling transactions.',
                'content'=>"1. Open Reports from the Admin dashboard.\n2. Use the transaction type filter to view all transactions, Product Purchases, or Farmer/Miller Milling transactions.\n3. Use status and date filters when you need a narrower report.\n4. Review the transaction totals and detailed records.\n5. Open an individual transaction when you need more information.\nThe Reports module is intended for centralized monitoring of recorded transactions.",
                'keywords'=>['reports','transaction','filter','product purchase','milling','status','date'], 'route_name'=>'admin.reports', 'sort_order'=>130,
            ],
            [
                'slug'=>'admin-vat', 'roles'=>['admin'], 'category'=>'Reports & Settings',
                'title'=>'How to Manage the Central VAT Setting', 'summary'=>'Open the VAT settings page and control the configured system VAT behavior.',
                'content'=>"1. Open Central VAT Setting from the Admin dashboard.\n2. Review the current VAT status and configured rate.\n3. Enter the desired rate and enable or disable VAT according to your system policy.\n4. Save the setting.\n5. The configured VAT setting is used by the transaction calculations that read the central setting.\nAlways verify the displayed total and invoice breakdown after changing a production VAT setting.",
                'keywords'=>['vat','tax','percentage','central setting','invoice'], 'route_name'=>'admin.reports.vat.settings', 'sort_order'=>140,
            ],
            [
                'slug'=>'admin-inventory', 'roles'=>['admin'], 'category'=>'Inventory',
                'title'=>'How to Manage Inventory', 'summary'=>'View inventory items and assign available palay to a Miller when the workflow requires it.',
                'content'=>"1. Open Inventory.\n2. Review the available purchased rice/palay stock and its current information.\n3. When a milling workflow requires assignment, open the assignment action for the relevant inventory item.\n4. Select the appropriate Miller and save the assignment.\n5. Continue monitoring the milling transaction from the Milling Requests area.",
                'keywords'=>['inventory','stock','palay','rice','assign','miller'], 'route_name'=>'admin.inventory', 'sort_order'=>150,
            ],
            [
                'slug'=>'admin-distribution', 'roles'=>['admin'], 'category'=>'Distribution',
                'title'=>'How to Manage Distribution', 'summary'=>'Create distribution records, schedule them, and mark completed distributions.',
                'content'=>"1. Open Distribution.\n2. Review existing distribution records.\n3. Create a distribution record when a new distribution activity needs to be recorded.\n4. Use Schedule for a distribution that needs a planned date/time.\n5. Use Complete when the distribution has actually been completed.\n6. Keep distribution information consistent with the underlying inventory and beneficiary records.",
                'keywords'=>['distribution','schedule','complete','beneficiary','rice assistance'], 'route_name'=>'admin.distribution', 'sort_order'=>160,
            ],
            [
                'slug'=>'admin-announcements', 'roles'=>['admin'], 'category'=>'Admin',
                'title'=>'How to Publish an Announcement', 'summary'=>'Create, archive, restore, and delete system announcements.',
                'content'=>"1. Open Announcements.\n2. Enter the announcement title and message.\n3. Save/publish the announcement.\n4. Use the archive action when an announcement should no longer be active.\n5. Use the announcement library to review archived items and restore an item when needed.\n6. Delete an announcement only when it should be permanently removed from the available records.",
                'keywords'=>['announcement','post','archive','restore','library'], 'route_name'=>'admin.announcements.index', 'sort_order'=>170,
            ],
            [
                'slug'=>'admin-milling', 'roles'=>['admin'], 'category'=>'Milling',
                'title'=>'How Admin Handles a Milling Transaction', 'summary'=>'Create, monitor, cancel, confirm completion, and view milling invoices.',
                'content'=>"Admin can act as a milling requester.\n\n1. Open Request Milling to create a milling request.\n2. Select the needed milling information and submit the request.\n3. Open My Milling Requests to monitor the transaction.\n4. Review the workflow status, schedule, payment, milling progress, and proof.\n5. When the Miller marks the request finished, the requester can confirm completion when the transaction requirements are satisfied.\n6. Use the invoice page/download when a transaction record is needed.",
                'keywords'=>['milling','request','miller','schedule','payment','proof','invoice'], 'route_name'=>'admin.milling.index', 'sort_order'=>180,
            ],

            // FARMER
            [
                'slug'=>'farmer-dashboard', 'roles'=>['farmer'], 'category'=>'Farmer',
                'title'=>'Farmer Dashboard Overview', 'summary'=>'Learn where to manage products, orders, milling, farm profile, and earnings.',
                'content'=>"The Farmer dashboard provides access to Farm Profile, Milling Requests, Products, Orders, Earnings, Notifications, and other available features.\n\nUse Products to publish and manage rice products.\nUse Orders to process buyer orders.\nUse Milling to request milling services and monitor requests.\nUse Earnings to review recorded earnings/analytics.\nUse Profile to maintain farm and account information.",
                'keywords'=>['farmer','dashboard','products','orders','milling','earnings'], 'route_name'=>'farmer.dashboard', 'sort_order'=>200,
            ],
            [
                'slug'=>'farmer-add-product', 'roles'=>['farmer'], 'category'=>'Products',
                'title'=>'How to Add a Rice Product', 'summary'=>'Create a rice product listing from the Farmer Products module.',
                'content'=>"1. Open Products.\n2. Select Add Product.\n3. Enter the product information required by the form, including the product name, pricing, available quantity, and image/details when requested.\n4. Submit the product.\n5. Return to Products to verify that the listing and stock information appear correctly.\nIf a product should not be offered, use the available toggle or stock action instead of creating a duplicate listing.",
                'keywords'=>['product','add','rice','listing','price','quantity','image'], 'route_name'=>'farmer.products.create', 'sort_order'=>210,
            ],
            [
                'slug'=>'farmer-product-stock', 'roles'=>['farmer'], 'category'=>'Products',
                'title'=>'How to Manage Product Stock', 'summary'=>'Toggle product availability, mark a product out of stock, and restock it.',
                'content'=>"1. Open Products.\n2. Find the product you want to manage.\n3. Use the availability toggle when you need to enable or disable the listing.\n4. Use Out of Stock when no sellable stock remains.\n5. Use Restock when additional quantity becomes available.\n6. Verify the displayed stock/availability after saving.",
                'keywords'=>['stock','restock','out of stock','toggle','availability'], 'route_name'=>'farmer.products.index', 'sort_order'=>220,
            ],
            [
                'slug'=>'farmer-orders', 'roles'=>['farmer'], 'category'=>'Orders',
                'title'=>'How to Process a Farmer Order', 'summary'=>'Follow the product order workflow from pending approval through delivery and buyer confirmation.',
                'content'=>"The current Farmer order workflow is: PENDING → APPROVED → PAID → Expected Delivery Date/Time → Out for Delivery → DELIVERED → Buyer confirms receipt → COMPLETED.\n\n1. Open Orders and review pending orders.\n2. Approve the order when it is valid.\n3. Mark it paid only when payment has actually been received according to your operating procedure.\n4. Set the expected delivery date/time.\n5. Start delivery when the order is dispatched.\n6. Mark the order delivered and provide the required delivery proof when applicable.\n7. The buyer confirms receipt; the buyer confirmation is what completes the final transaction workflow.",
                'keywords'=>['orders','pending','approve','paid','delivery','delivered','complete','received'], 'route_name'=>'farmer.orders.index', 'sort_order'=>230,
            ],
            [
                'slug'=>'farmer-milling', 'roles'=>['farmer'], 'category'=>'Milling',
                'title'=>'How to Request Milling as a Farmer', 'summary'=>'Create a milling request and monitor it until the requester confirms completion.',
                'content'=>"1. Open Request Milling.\n2. Enter the required milling information and submit the request.\n3. Open Milling Requests to monitor the request status.\n4. Review the selected Miller, schedule, payment status, milling progress, and proof when available.\n5. When the Miller finishes the request and the transaction requirements are satisfied, use Confirm Completed to finalize the requester side of the workflow.",
                'keywords'=>['milling','request','farmer','miller','schedule','confirm completed'], 'route_name'=>'farmer.milling.create', 'sort_order'=>240,
            ],
            [
                'slug'=>'farmer-earnings', 'roles'=>['farmer'], 'category'=>'Earnings',
                'title'=>'How to View Farmer Earnings', 'summary'=>'Review recorded earnings and analytics from the Farmer Earnings page.',
                'content'=>"1. Open Earnings from the Farmer dashboard.\n2. Review the available transaction/earning summaries.\n3. Use the displayed information to monitor product and related transaction performance.\nThe page reflects recorded system data; it should not be treated as a separate payment processor or bank statement.",
                'keywords'=>['earnings','income','analytics','farmer'], 'route_name'=>'farmer.earnings.index', 'sort_order'=>250,
            ],

            // MILLER
            [
                'slug'=>'miller-dashboard', 'roles'=>['miller'], 'category'=>'Miller',
                'title'=>'Miller Dashboard Overview', 'summary'=>'Learn where to manage requests, schedule, reports, earnings, and profile.',
                'content'=>"Use Requests to handle incoming milling transactions.\nUse Schedule to manage scheduled milling activities.\nUse Reports to review milling transaction information.\nUse Earnings to review recorded earnings.\nUse Profile to maintain your account information.\nUse Notifications to review transaction alerts.",
                'keywords'=>['miller','dashboard','requests','schedule','reports','earnings'], 'route_name'=>'miller.dashboard', 'sort_order'=>300,
            ],
            [
                'slug'=>'miller-requests', 'roles'=>['miller'], 'category'=>'Milling',
                'title'=>'How a Miller Processes a Milling Request', 'summary'=>'Process a milling request from acceptance through finishing and requester confirmation.',
                'content'=>"The current Miller workflow is: PENDING → ACCEPTED → SCHEDULED + MILLING FEE → PAID → IN PROGRESS → FINISHED + PROOF → REQUESTER CONFIRMS → COMPLETED.\n\n1. Open Requests.\n2. Accept a request when you can provide the service, or Reject it when appropriate.\n3. Set the schedule and milling fee according to the transaction workflow.\n4. Mark the transaction paid when payment is actually received.\n5. Start milling when the scheduled work begins.\n6. Finish the milling transaction and provide the required proof.\n7. The requester (Farmer/Admin, depending on the transaction) confirms completion. Miller should not independently finalize the transaction as COMPLETED when requester confirmation is required.",
                'keywords'=>['milling','accept','reject','schedule','paid','start','finish','proof','completed'], 'route_name'=>'miller.requests', 'sort_order'=>310,
            ],
            [
                'slug'=>'miller-schedule', 'roles'=>['miller'], 'category'=>'Milling',
                'title'=>'How to Manage the Miller Schedule', 'summary'=>'Open the schedule and set the schedule for an eligible milling request.',
                'content'=>"1. Open Schedule to review scheduled milling activities.\n2. When a request needs scheduling, use the request/schedule action available to you.\n3. Set the appropriate date/time based on your actual availability.\n4. Save the schedule and verify that the request reflects the scheduled information.",
                'keywords'=>['schedule','calendar','date','time','milling'], 'route_name'=>'miller.schedule', 'sort_order'=>320,
            ],
            [
                'slug'=>'miller-reports', 'roles'=>['miller'], 'category'=>'Reports',
                'title'=>'How to View Miller Reports', 'summary'=>'Review milling transaction reports available to the Miller account.',
                'content'=>"Open Reports from the Miller dashboard. Review the transaction information presented by the system, including the available status and transaction details. Use this page for operational monitoring and record checking.",
                'keywords'=>['reports','miller','transactions','status'], 'route_name'=>'miller.reports', 'sort_order'=>330,
            ],
            [
                'slug'=>'miller-open-status', 'roles'=>['miller'], 'category'=>'Miller',
                'title'=>'How to Set Miller Availability', 'summary'=>'Control the Miller open/available status from the dashboard.',
                'content'=>"The Miller dashboard includes an availability/open status. Use the available toggle/action to indicate whether you are open to receive milling requests. Keep the status accurate so requesters can make informed choices about available milling service providers.",
                'keywords'=>['open','available','availability','toggle','miller'], 'route_name'=>'miller.dashboard', 'sort_order'=>340,
            ],

            // RESIDENT
            [
                'slug'=>'resident-dashboard', 'roles'=>['resident'], 'category'=>'Resident',
                'title'=>'Resident Dashboard Overview', 'summary'=>'Learn how to access Marketplace, Orders, Profile, and other resident features.',
                'content'=>"Use Marketplace to browse available rice products.\nUse Product Details to review a specific product.\nUse Checkout to place an order.\nUse Orders to track your transaction.\nUse the Invoice page to view/download the order invoice when available.\nUse Profile to maintain your account information.",
                'keywords'=>['resident','dashboard','marketplace','orders','checkout','invoice'], 'route_name'=>'resident.dashboard', 'sort_order'=>400,
            ],
            [
                'slug'=>'resident-browse-marketplace', 'roles'=>['resident'], 'category'=>'Marketplace',
                'title'=>'How to Browse the Marketplace', 'summary'=>'Find rice products and open product details before ordering.',
                'content'=>"1. Open Marketplace from the Resident dashboard.\n2. Browse the available farmer rice products.\n3. Select a product to open its details.\n4. Review the product information, price, available quantity, and seller information shown by the system.\n5. Continue to Checkout when you are ready to place an order.",
                'keywords'=>['marketplace','rice','product','browse','farmer','seller'], 'route_name'=>'resident.marketplace', 'sort_order'=>410,
            ],
            [
                'slug'=>'resident-place-order', 'roles'=>['resident'], 'category'=>'Orders',
                'title'=>'How to Place a Rice Product Order', 'summary'=>'Browse a product, review checkout information, and submit an order.',
                'content'=>"1. Open Marketplace and select a product.\n2. Open the product details and choose the quantity you want, within the available stock.\n3. Continue to Checkout.\n4. Review the order items, quantity, pricing, VAT when enabled, and any applicable shipping/delivery cost shown by the system.\n5. Review pickup/delivery information and other checkout details.\n6. Submit the order.\n7. Open Orders to monitor the transaction status and open the invoice/success page when available.",
                'keywords'=>['checkout','place order','order','quantity','vat','shipping','pickup','delivery'], 'route_name'=>'resident.marketplace', 'sort_order'=>420,
            ],
            [
                'slug'=>'resident-order-tracking', 'roles'=>['resident'], 'category'=>'Orders',
                'title'=>'How to Track and Confirm an Order', 'summary'=>'View your orders and confirm receipt after delivery.',
                'content'=>"1. Open Orders.\n2. Select an order to view its details and current status.\n3. Review delivery/pickup information and available transaction details.\n4. When the order has actually been received, use the confirmation action provided by the system.\n5. The buyer confirmation is part of the final order completion workflow.",
                'keywords'=>['orders','track','status','received','confirm','delivery'], 'route_name'=>'resident.orders.index', 'sort_order'=>430,
            ],
            [
                'slug'=>'resident-invoice', 'roles'=>['resident'], 'category'=>'Orders & Invoice',
                'title'=>'How to View or Download an Order Invoice', 'summary'=>'Open the invoice from an order and download the PDF when available.',
                'content'=>"1. Open Orders.\n2. Select the relevant completed/eligible order.\n3. Open the Invoice option when available.\n4. Review the invoice details, including transaction totals and applicable breakdowns shown by the system.\n5. Use Download when you need a PDF copy.",
                'keywords'=>['invoice','receipt','pdf','download','order'], 'route_name'=>'resident.orders.index', 'sort_order'=>440,
            ],
            [
                'slug'=>'resident-checkout-costs', 'roles'=>['resident'], 'category'=>'Checkout',
                'title'=>'What Do the Checkout Amounts Mean?', 'summary'=>'Understand the total displayed at checkout, including VAT and shipping when applicable.',
                'content'=>"The checkout page is the source of truth for the amount you are asked to pay for the current order. Depending on the configured system settings and order type, the total may include product subtotal, VAT when enabled, and an applicable delivery/shipping cost.\n\nPickup and delivery can have different cost behavior. Review the displayed breakdown before submitting the order. The invoice provides the transaction record after the order is created.",
                'keywords'=>['vat','shipping fee','pickup','delivery','subtotal','total','checkout'], 'route_name'=>'resident.marketplace', 'sort_order'=>450,
            ],

            // CROSS-FUNCTIONAL TROUBLESHOOTING
            [
                'slug'=>'troubleshoot-product-image', 'roles'=>['all'], 'category'=>'Troubleshooting',
                'title'=>'Product Image Is Not Showing', 'summary'=>'Basic checks when a rice product image does not display.',
                'content'=>"If a product image is missing: 1. Open the product and confirm an image was actually uploaded. 2. Refresh the product/marketplace page. 3. Confirm the product is still active and the image file exists on the server storage used by ANI-CARE. 4. If the image still returns a 404 or broken image, this is usually a server storage/symbolic-link/path issue rather than a marketplace data issue. Contact the system administrator and provide the product name and image filename so the server path can be checked.",
                'keywords'=>['image','picture','photo','404','broken image','product image','storage'], 'sort_order'=>500,
            ],
            [
                'slug'=>'troubleshoot-order', 'roles'=>['all'], 'category'=>'Troubleshooting',
                'title'=>'My Order Is Not Showing or Status Looks Wrong', 'summary'=>'Checks for missing orders or unexpected order status.',
                'content'=>"First open the Orders page for your role and refresh the page. Confirm that you are logged into the correct account. If the order exists but the status is unexpected, review the order details and the most recent notification.\n\nFor Farmers, order status depends on the configured approval/payment/delivery workflow. For Residents, final completion requires buyer receipt confirmation. If the problem persists, provide the order ID to the administrator instead of creating a duplicate order.",
                'keywords'=>['order','missing','status','not showing','duplicate'], 'sort_order'=>510,
            ],
            [
                'slug'=>'troubleshoot-milling', 'roles'=>['all'], 'category'=>'Troubleshooting',
                'title'=>'My Milling Request Is Not Moving', 'summary'=>'Check the current milling workflow status before taking another action.',
                'content'=>"Check the request status first. A milling transaction can move through pending, acceptance, scheduling, payment, in-progress, finished/proof, requester confirmation, and completed stages depending on the role.\n\nIf you are the requester, wait for the Miller action required at the current stage. If you are the Miller, complete only the action assigned to your current stage. Avoid creating a second milling request for the same transaction unless the administrator instructs you to do so.",
                'keywords'=>['milling','pending','accepted','scheduled','paid','in progress','finished','completed'], 'sort_order'=>520,
            ],
        ];

        foreach ($articles as $article) {
            $article['is_active'] = true;
            $article['keywords'] = array_values(array_unique($article['keywords'] ?? []));
            HelpArticle::updateOrCreate(['slug' => $article['slug']], $article);
        }
    }
}
