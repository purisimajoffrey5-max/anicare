<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Terms and Conditions | ANI-CARE Allacapan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f5f8f6;
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
        }

        .terms-header {
            background: linear-gradient(135deg, #198754, #146c43);
            color: white;
            padding: 45px 20px;
            text-align: center;
        }

        .terms-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .terms-header p {
            margin-bottom: 5px;
        }

        .terms-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .terms-card {
            background: white;
            border-radius: 14px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .terms-card h2 {
            color: #198754;
            font-size: 1.35rem;
            font-weight: 700;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .terms-card h2:first-child {
            margin-top: 0;
        }

        .terms-card p {
            line-height: 1.7;
        }

        .terms-card ul {
            line-height: 1.8;
        }

        .effective-date {
            color: #666;
            font-size: 0.95rem;
        }

        .back-button {
            margin-bottom: 25px;
        }

        .terms-footer {
            background: #198754;
            color: white;
            text-align: center;
            padding: 25px 15px;
            margin-top: 40px;
        }

        .terms-footer a {
            color: white;
            text-decoration: none;
            margin: 0 8px;
        }

        .terms-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .terms-card {
                padding: 25px;
            }

            .terms-header {
                padding: 35px 15px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="terms-header">
        <h1>ANI-CARE ALLACAPAN</h1>

        <p class="fs-5">
            LGU Rice Assistance & Marketplace System
        </p>

        <p>
            <i class="bi bi-file-earmark-text"></i>
            Terms and Conditions
        </p>
    </div>


    <!-- CONTENT -->
    <div class="terms-container">

        <div class="back-button">
            <a href="{{ route('main') }}" class="btn btn-success">
                <i class="bi bi-arrow-left"></i>
                Back to ANI-CARE
            </a>
        </div>

        <div class="terms-card">

            <h2>1. Acceptance of Terms</h2>

            <p>
                By accessing or using ANI-CARE Allacapan, you agree to comply
                with these Terms and Conditions. If you do not agree with
                these Terms, you should not use the system.
            </p>

            <p>
                ANI-CARE Allacapan is a web-based system designed to support
                the LGU's rice assistance, inventory, distribution, milling,
                and local rice/palay marketplace operations.
            </p>


            <h2>2. User Accounts</h2>

            <p>
                Users may register for an account by providing the information
                required by the system.
            </p>

            <p>
                Depending on the account type, registration may require
                information such as:
            </p>

            <ul>
                <li>Full name</li>
                <li>Username</li>
                <li>Barangay</li>
                <li>User role</li>
                <li>Password</li>
                <li>Email address, when applicable</li>
                <li>Location information, when applicable</li>
            </ul>

            <p>
                The current registration workflow requires administrator
                approval for roles other than Admin before those accounts
                can log in.
            </p>

            <p>Users are responsible for:</p>

            <ul>
                <li>Keeping their login credentials confidential</li>
                <li>Providing accurate information</li>
                <li>Not sharing their account with unauthorized persons</li>
                <li>Immediately reporting suspected unauthorized access</li>
            </ul>


            <h2>3. User Roles</h2>

            <p>
                ANI-CARE uses role-based access. The current system supports:
            </p>

            <ul>
                <li>
                    <strong>Admin</strong> — manages approvals, inventory,
                    distribution, marketplace information, announcements,
                    and related administrative functions.
                </li>

                <li>
                    <strong>Farmer</strong> — manages farm information,
                    rice/palay products, milling requests, and orders.
                </li>

                <li>
                    <strong>Miller</strong> — manages milling availability,
                    milling requests, schedules, and milling reports.
                </li>

                <li>
                    <strong>Resident</strong> — browses marketplace products,
                    places orders, manages profile information, and tracks orders.
                </li>
            </ul>

            <p>
                Users must only access functions authorized for their
                assigned role.
            </p>


            <h2>4. Marketplace and Products</h2>

            <p>
                Farmers may post rice or palay products with information
                such as product name, type, price per kilogram, available
                quantity, and optional photos.
            </p>

            <p>
                Users must provide truthful and accurate product information.
            </p>

            <p>
                Products may become unavailable when their available stock
                reaches zero.
            </p>


            <h2>5. Orders and Checkout</h2>

            <p>
                Residents may place orders for available products.
            </p>

            <p>The current checkout process supports:</p>

            <ul>
                <li>Delivery or pickup</li>
                <li>Quantity in kilograms</li>
                <li>Contact information</li>
                <li>Delivery/pickup address</li>
                <li>Available payment methods</li>
                <li>Optional notes</li>
            </ul>

            <p>
                The system validates product availability before creating
                an order.
            </p>

            <p>
                Users are responsible for reviewing their order information
                before submitting it.
            </p>


            <h2>6. Milling Services</h2>

            <p>
                Farmers may submit milling requests to available millers.
            </p>

            <p>
                A farmer must provide the required milling information and
                select an available miller. The system can prevent requests
                from being made to millers whose service is marked CLOSED.
            </p>

            <p>
                Millers may approve, reject, schedule, and complete milling
                requests according to their authorized system functions.
            </p>


            <h2>7. Location and Maps</h2>

            <p>
                ANI-CARE may use user-provided latitude and longitude
                information to support:
            </p>

            <ul>
                <li>Farmer locations</li>
                <li>Miller locations</li>
                <li>Delivery/order tracking</li>
                <li>Map displays</li>
                <li>Distance-related calculations</li>
            </ul>

            <p>
                Users should provide accurate location information where
                required by the system.
            </p>


            <h2>8. Prohibited Activities</h2>

            <p>Users must not:</p>

            <ul>
                <li>Use another person's account without authorization</li>
                <li>Attempt to bypass role-based restrictions</li>
                <li>Submit false or misleading information</li>
                <li>Manipulate product, inventory, order, or distribution records</li>
                <li>Attempt unauthorized access to the database or application</li>
                <li>Interfere with system operation</li>
                <li>Upload malicious files or code</li>
                <li>Attempt to obtain passwords or authentication information from other users</li>
                <li>Abuse marketplace, milling, distribution, or account functions</li>
            </ul>

            <p>
                Unauthorized access attempts may be subject to system
                monitoring and administrative action.
            </p>


            <h2>9. Account Suspension or Revocation</h2>

            <p>
                ANI-CARE administrators may restrict, suspend, or revoke
                an account when there is a legitimate administrative or
                security reason, including violation of these Terms,
                misuse of the system, or unauthorized activity.
            </p>

            <p>
                The system provides administrative approval and revocation
                functionality for user accounts.
            </p>


            <h2>10. Security</h2>

            <p>
                Users must maintain the confidentiality of their credentials
                and should use strong passwords.
            </p>

            <p>
                ANI-CARE may record application-level security events,
                including certain suspicious requests and
                authentication-related events, for security monitoring
                and system administration.
            </p>

            <div class="alert alert-warning">
                <i class="bi bi-shield-exclamation"></i>
                <strong>Security Notice:</strong>
                Security monitoring should not be represented as a replacement
                for dedicated antivirus, server security, or other
                infrastructure security controls.
            </div>


            <h2>11. System Availability</h2>

            <p>
                ANI-CARE is intended to provide continuous access to its
                supported functions, but availability may be affected by:
            </p>

            <ul>
                <li>Maintenance</li>
                <li>Server problems</li>
                <li>Internet connectivity</li>
                <li>Hosting issues</li>
                <li>Software updates</li>
                <li>Other circumstances outside the application's control</li>
            </ul>


            <h2>12. Data and User Information</h2>

            <p>
                Users should provide accurate information when using ANI-CARE.
            </p>

            <p>
                Information submitted through the system may be used to
                operate the relevant system functions, including account
                management, marketplace transactions, milling requests,
                distributions, notifications, and order tracking.
            </p>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Privacy Notice:</strong>
                This section should eventually be coordinated with a
                separate Privacy Policy that identifies exactly what
                personal information is collected, why it is collected,
                how long it is retained, and who may access it.
            </div>


            <h2>13. Administrative Records</h2>

            <p>
                System activities may generate records necessary for
                administration, auditing, transaction management,
                security monitoring, and system recovery.
            </p>

            <p>
                Users should not attempt to alter, delete, or manipulate
                system records without authorization.
            </p>


            <h2>14. Changes to These Terms</h2>

            <p>
                These Terms and Conditions may be updated when ANI-CARE
                functionality, policies, or operational requirements change.
            </p>

            <p>
                The updated version should indicate its effective date.
            </p>


            <h2>15. Contact and Support</h2>

            <p>
                For questions, account concerns, transaction issues, or
                system-related assistance, users should contact the
                appropriate ANI-CARE/LGU administrator or designated
                support personnel.
            </p>

            <hr class="my-4">

            <p class="effective-date mb-0">
                <strong>Effective Date:</strong> September 30, 2026
            </p>

        </div>
    </div>


    <!-- FOOTER -->
    <footer class="terms-footer">

        <div>
            <strong>ANI-CARE ALLACAPAN</strong>
        </div>

        <div class="mt-2">
            LGU Rice Assistance & Marketplace System
        </div>

        <div class="mt-3">
            <a href="{{ route('main') }}">
                Home
            </a>

            <span>•</span>

            <a href="{{ route('terms') }}">
                Terms and Conditions
            </a>
        </div>

        <div class="mt-3">
            © 2026 All Rights Reserved |
            Allacapan, Cagayan
        </div>

    </footer>

</body>
</html>