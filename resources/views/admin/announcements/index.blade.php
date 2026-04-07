<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Announcements | ANI-CARE Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6fb;
    font-family: "Segoe UI", sans-serif;
}

.wrap{
    max-width:1150px;
    margin:auto;
    padding:30px 18px 80px;
}

/* NAVBAR */
.navbar-brand{
    font-weight:700;
    letter-spacing:.5px;
}

/* CARDS */
.card-soft{
    border:none;
    border-radius:16px;
    box-shadow:0 6px 18px rgba(0,0,0,0.06);
}

/* FORM STYLE */
.form-control{
    border-radius:10px;
}

.btn-soft{
    border-radius:10px;
    padding:6px 16px;
}

/* ANNOUNCEMENT TABLE */
.table thead{
    background:#f8f9fc;
}

.table td{
    vertical-align:middle;
}

/* HEADER AREA */
.page-title{
    font-weight:700;
}

.page-sub{
    font-size:.9rem;
    color:#6c757d;
}

/* NOTICE PANEL */

.notice-panel{
    background:#e9f7ef;
    border-left:4px solid #198754;
    padding:12px 16px;
    border-radius:8px;
    font-size:.9rem;
}

</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark" style="background:#198754;">
<div class="container-fluid px-4">

<span class="navbar-brand">ANI-CARE ALLACAPAN | Admin Panel</span>

<div class="d-flex gap-2">

<a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm btn-soft">
Dashboard
</a>

<a href="{{ route('admin.announcements.library') }}" class="btn btn-outline-light btn-sm btn-soft">
Announcement Library
</a>

<form method="POST" action="{{ route('logout') }}" class="m-0">
@csrf
<button class="btn btn-warning btn-sm btn-soft">
Logout
</button>
</form>

</div>
</div>
</nav>

<div class="wrap">

<!-- HEADER -->
<div class="mb-4">

<h3 class="page-title text-success mb-1">
System Announcements
</h3>

<div class="page-sub">
Manage official announcements that will appear on the dashboards of all system users.
</div>

</div>

<!-- NOTICE -->
<div class="notice-panel mb-4">
Announcements posted here will automatically appear on the dashboards of residents, farmers, and millers.  
Use this feature to publish important system updates, announcements, or public notices.
</div>

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
{{ $errors->first() }}
</div>
@endif


<!-- CREATE ANNOUNCEMENT -->
<div class="card card-soft p-4 mb-4">

<h5 class="fw-semibold mb-3">
Create New Announcement
</h5>

<form method="POST" action="{{ route('admin.announcements.store') }}">
@csrf

<div class="row g-3">

<div class="col-md-4">
<label class="form-label small">Announcement Title</label>
<input type="text" name="title" class="form-control"
maxlength="120" required value="{{ old('title') }}">
</div>

<div class="col-md-8">
<label class="form-label small">Announcement Message</label>
<textarea name="message" rows="2" class="form-control"
maxlength="5000" required>{{ old('message') }}</textarea>
</div>

<div class="col-12 d-flex justify-content-end mt-2">
<button class="btn btn-success btn-soft px-4">
Post Announcement
</button>
</div>

</div>

</form>

</div>


<!-- ACTIVE ANNOUNCEMENTS -->
<div class="card card-soft p-4">

<h5 class="fw-semibold mb-3">
Active Announcements
</h5>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>
<tr>
<th>ID</th>
<th>Title</th>
<th>Announcement</th>
<th>Date Posted</th>
<th class="text-end">Action</th>
</tr>
</thead>

<tbody>

@forelse($announcements as $a)

<tr>

<td class="fw-semibold text-muted">
#{{ $a->id }}
</td>

<td class="fw-semibold">
{{ $a->title }}
</td>

<td class="text-muted">
{{ $a->message }}
</td>

<td class="small text-muted">
{{ $a->created_at?->format('F d, Y  H:i') }}
</td>

<td class="text-end">

<form method="POST"
action="{{ route('admin.announcements.archive', $a->id) }}"
class="d-inline">

@csrf

<button class="btn btn-outline-success btn-sm btn-soft"
onclick="return confirm('Mark this announcement as completed? It will be moved to the library.')">

Mark as Completed

</button>

</form>

</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center text-muted py-4">
No active announcements available.
</td>
</tr>

@endforelse

</tbody>
</table>

</div>

<div class="mt-3">
{{ $announcements->links() }}
</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>