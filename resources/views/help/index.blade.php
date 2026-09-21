<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Help Center | ANI-CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f5f8f6; color:#26332c; }
        .hero { background:linear-gradient(135deg,#198754,#146c43); color:#fff; border-radius:0 0 28px 28px; }
        .article-card { border:0; border-radius:18px; transition:.18s ease; height:100%; }
        .article-card:hover { transform:translateY(-2px); box-shadow:0 .5rem 1.2rem rgba(0,0,0,.08); }
        .category-pill { background:#e8f5ee; color:#146c43; border-radius:999px; padding:.35rem .7rem; font-size:.78rem; font-weight:700; }
        .chat-card { border:0; border-radius:20px; }
    </style>
</head>
<body>
<div class="hero py-5 mb-4">
    <div class="container">
        <a href="{{ route($role.'.dashboard') }}" class="btn btn-light btn-sm mb-3"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        <h1 class="fw-bold"><i class="bi bi-life-preserver"></i> ANI-CARE Help Center</h1>
        <p class="mb-0">Step-by-step guides for your <strong>{{ ucfirst($role) }}</strong> account.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <form class="card card-body shadow-sm border-0 rounded-4 mb-4" method="GET" action="{{ route('help.index') }}">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input name="q" value="{{ $search }}" class="form-control" placeholder="Search how to use ANI-CARE...">
                    <button class="btn btn-success">Search</button>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('help.index') }}" class="btn btn-sm {{ !$category ? 'btn-success' : 'btn-outline-success' }}">All</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('help.index', ['category' => $cat]) }}" class="btn btn-sm {{ $category === $cat ? 'btn-success' : 'btn-outline-success' }}">{{ $cat }}</a>
                    @endforeach
                </div>
            </form>

            <div class="row g-3">
                @forelse($articles as $article)
                    <div class="col-md-6">
                        <a href="{{ route('help.article', $article->slug) }}" class="text-decoration-none text-dark">
                            <div class="card article-card shadow-sm p-3">
                                <div class="mb-2"><span class="category-pill">{{ $article->category }}</span></div>
                                <h5 class="fw-bold mb-2">{{ $article->title }}</h5>
                                <p class="text-muted small mb-0">{{ $article->summary }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-light border rounded-4">No help articles matched your search.</div></div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card chat-card shadow-sm p-4 sticky-lg-top" style="top:20px">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-circle bg-success text-white p-2"><i class="bi bi-robot"></i></div>
                    <div><h5 class="fw-bold mb-0">ANI-CARE Assistant</h5><small class="text-muted">Help based on your role</small></div>
                </div>
                <p class="small text-muted">Ask a question about operating the system. The assistant searches the official ANI-CARE Help Center content for your account role.</p>
                <form id="helpChatForm">
                    @csrf
                    <textarea id="helpMessage" class="form-control mb-2" rows="4" placeholder="Example: How do I add a rice product?"></textarea>
                    <button class="btn btn-success w-100"><i class="bi bi-send"></i> Ask</button>
                </form>
                <div id="helpChatAnswer" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
const form = document.getElementById('helpChatForm');
const answerBox = document.getElementById('helpChatAnswer');
const messageBox = document.getElementById('helpMessage');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const message = messageBox.value.trim();

    if (!message) {
        return;
    }

    answerBox.innerHTML = `
        <div class="text-muted small">
            <span class="spinner-border spinner-border-sm me-1"></span>
            Searching ANI-CARE Help Center...
        </div>
    `;

    try {
        const response = await fetch('{{ route('help.chat', [], false) }}', {
            method: 'POST',

            credentials: 'same-origin',
            
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message,
                current_route: @json(request()->route()?->getName())
            })
        });

        const contentType = response.headers.get('content-type') || '';

        if (!response.ok) {
            const errorText = await response.text();

            console.error('ANI-CARE Help Error:', {
                status: response.status,
                statusText: response.statusText,
                response: errorText
            });

            let messageText = '';

            if (response.status === 419) {
                messageText =
                    'Your session or CSRF token has expired. Please refresh the page and try again.';
            } else if (response.status === 404) {
                messageText =
                    'The ANI-CARE Help Assistant route was not found. Please check the help.chat route.';
            } else if (response.status === 500) {
                messageText =
                    'The Help Assistant encountered a Laravel server error. Please check storage/logs/laravel.log.';
            } else {
                messageText =
                    `Help Assistant returned HTTP ${response.status}.`;
            }

            answerBox.innerHTML = `
                <div class="alert alert-warning small">
                    <strong>ANI-CARE Assistant Error</strong><br>
                    ${escapeHtml(messageText)}
                </div>
            `;

            return;
        }

        if (!contentType.includes('application/json')) {
            const text = await response.text();

            console.error('Expected JSON but received:', text);

            answerBox.innerHTML = `
                <div class="alert alert-warning small">
                    The Help Assistant returned an unexpected response.
                    Please check the Laravel logs.
                </div>
            `;

            return;
        }

        const data = await response.json();

        let html = `
            <div class="bg-light rounded-4 p-3 small"
                 style="white-space:pre-line">
                ${escapeHtml(data.answer || 'No answer found.')}
            </div>
        `;

        if (data.article?.url) {
            html += `
                <a class="btn btn-sm btn-outline-success mt-2"
                   href="${escapeHtml(data.article.url)}">
                    Open full guide
                </a>
            `;
        }

        if (data.suggestions?.length) {
            html += `
                <div class="mt-3">
                    <div class="fw-bold small mb-2">
                        Related guides
                    </div>

                    <div class="d-grid gap-1">
            `;

            data.suggestions.forEach(s => {
                html += `
                    <a class="btn btn-sm btn-light text-start"
                       href="{{ route('help.index') }}?q=${encodeURIComponent(s.title)}">
                        ${escapeHtml(s.title)}
                    </a>
                `;
            });

            html += `
                    </div>
                </div>
            `;
        }

        answerBox.innerHTML = html;

    } catch (error) {

        console.error('ANI-CARE Help Assistant fetch error:', error);

        answerBox.innerHTML = `
            <div class="alert alert-danger small">
                <strong>Connection Error</strong><br>
                The Help Assistant could not connect to the ANI-CARE server.
                Check your browser console and Laravel logs.
            </div>
        `;
    }
});

function escapeHtml(value) {
    return String(value).replace(/[&<>'"]/g, c => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#039;',
        '"': '&quot;'
    }[c]));
}
</script>
</body>
</html>
