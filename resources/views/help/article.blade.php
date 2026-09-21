<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} | ANI-CARE Help</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f5f8f6; }
        .content { white-space:pre-line; line-height:1.75; }
        .article-shell { max-width:900px; }
    </style>
</head>
<body>
<div class="container article-shell py-5">
    <a href="{{ route('help.index') }}" class="btn btn-outline-success btn-sm mb-4"><i class="bi bi-arrow-left"></i> Help Center</a>
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        <span class="badge text-bg-success align-self-start mb-3">{{ $article->category }}</span>
        <h1 class="fw-bold">{{ $article->title }}</h1>
        <p class="text-muted">{{ $article->summary }}</p>
        <hr>
        <div class="content">{{ $article->content }}</div>
        @if($article->route_name && Route::has($article->route_name))
            <a href="{{ route($article->route_name) }}" class="btn btn-success mt-4">Open this module</a>
        @endif
    </div>
</div>
</body>
</html>
