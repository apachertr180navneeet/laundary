<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #1F446E; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .hero { background: linear-gradient(135deg, #1F446E 0%, #2a6a9e 100%); min-height: 80vh; display: flex; align-items: center; }
        .btn-premium { background: var(--primary); color: #fff; border: none; padding: 12px 32px; border-radius: 8px; font-weight: 600; text-decoration: none; }
        .btn-premium:hover { background: #163454; color: #fff; }
        .feature-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.08); transition: transform .2s; }
        .feature-card:hover { transform: translateY(-4px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background:var(--primary);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">LaundryMS</a>

        </div>
    </nav>

    <section class="hero text-white">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Professional Laundry Management</h1>
            <p class="lead mb-5 opacity-75">Streamline your laundry business with our comprehensive management system</p>

        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100">
                        <h5 class="fw-bold">Order Management</h5>
                        <p class="text-muted mb-0">Track orders from pickup to delivery with real-time status updates.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100">
                        <h5 class="fw-bold">Client Management</h5>
                        <p class="text-muted mb-0">Maintain comprehensive client records and order history.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100">
                        <h5 class="fw-bold">Billing & Invoicing</h5>
                        <p class="text-muted mb-0">Generate professional invoices, receipts, and GST bills.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 text-center text-muted" style="background:#f8f9fa;">
        <div class="container">&copy; {{ date('Y') }} LaundryMS. All rights reserved.</div>
    </footer>
</body>
</html>
