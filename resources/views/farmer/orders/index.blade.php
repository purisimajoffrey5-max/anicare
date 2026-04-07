<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Resident Orders | Farmer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{ background:#f5f7fb; }
    .wrap{ max-width:1200px; margin:0 auto; padding:22px 14px 70px; }
    .soft{ border:1px solid rgba(0,0,0,.06); border-radius:16px; background:#fff; }
    .img{ width:72px; height:56px; object-fit:cover; border-radius:10px; background:#e9ecef; }
    .btn-soft{ border-radius:12px; }
    .section-stat{
      border:1px solid rgba(0,0,0,.06);
      border-radius:16px;
      background:#fff;
      padding:16px;
      height:100%;
      box-shadow:0 6px 18px rgba(15,23,42,.04);
    }
    .order-meta-label{
      font-size:12px;
      color:#6c757d;
      margin-bottom:4px;
    }
    .order-meta-value{
      font-weight:600;
      color:#212529;
    }
    .status-pill{
      padding:6px 12px;
      border-radius:999px;
      font-size:12px;
      font-weight:700;
      display:inline-block;
    }
    .status-pending{ background:#fff3cd; color:#7a5a00; }
    .status-approved{ background:#cfe2ff; color:#084298; }
    .status-completed{ background:#d1e7dd; color:#0f5132; }
    .status-cancelled{ background:#e2e3e5; color:#41464b; }
    .modal-info-box{
      border:1px solid rgba(0,0,0,.06);
      border-radius:14px;
      padding:14px;
      background:#fafafa;
      height:100%;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark" style="background:#198754;">
  <div class="container-fluid px-3">
    <span class="navbar-brand fw-bold m-0">ANI-CARE | Farmer</span>
    <div class="d-flex gap-2">
      <a href="{{ route('farmer.dashboard') }}" class="btn btn-light btn-sm btn-soft">Back</a>
      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button class="btn btn-warning btn-sm btn-soft">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="wrap">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-success m-0">Orders from Residents</h3>
      <div class="text-muted small">All orders placed to your products.</div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  {{-- Quick Stats --}}
  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="section-stat">
        <div class="text-muted small">Total Orders</div>
        <div class="fs-4 fw-bold">{{ $orders->total() }}</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="section-stat">
        <div class="text-muted small">Pending</div>
        <div class="fs-4 fw-bold text-warning">
          {{ $orders->where('status', 'pending')->count() }}
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="section-stat">
        <div class="text-muted small">Approved</div>
        <div class="fs-4 fw-bold text-primary">
          {{ $orders->where('status', 'approved')->count() }}
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="section-stat">
        <div class="text-muted small">Completed</div>
        <div class="fs-4 fw-bold text-success">
          {{ $orders->where('status', 'completed')->count() }}
        </div>
      </div>
    </div>
  </div>

  <div class="soft p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Resident</th>
            <th>Kilos</th>
            <th>Unit</th>
            <th>Total</th>
            <th>Status</th>
            <th>When</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
        @forelse($orders as $o)
          @php
            $img = !empty($o->product?->photo_path) ? asset('storage/'.$o->product->photo_path) : null;
            $st  = strtolower($o->status ?? 'pending');
          @endphp

          <tr>
            <td class="fw-semibold">#{{ $o->id }}</td>

            <td>
              <div class="d-flex gap-2 align-items-center">
                @if($img)
                  <img src="{{ $img }}" class="img" alt="photo">
                @else
                  <div class="img d-flex align-items-center justify-content-center text-muted small">No photo</div>
                @endif
                <div>
                  <div class="fw-semibold">{{ $o->product?->name ?? '-' }}</div>
                  <div class="text-muted small text-uppercase">
                    {{ $o->product?->type ?? '' }}
                  </div>
                </div>
              </div>
            </td>

            <td>
              <div class="fw-semibold">{{ $o->resident?->fullname ?? $o->resident?->username ?? '-' }}</div>
              <div class="text-muted small">{{ $o->resident?->email ?? '' }}</div>
              @if(!empty($o->resident?->barangay))
                <div class="text-muted small">🏘 {{ $o->resident?->barangay }}</div>
              @endif
            </td>

            <td class="fw-semibold">{{ number_format((float)$o->quantity_kilos, 2) }} kg</td>
            <td>₱{{ number_format((float)$o->unit_price, 2) }}</td>
            <td class="fw-bold text-success">₱{{ number_format((float)$o->total_price, 2) }}</td>

            <td>
              @if($st === 'pending')
                <span class="status-pill status-pending">PENDING</span>
              @elseif($st === 'approved')
                <span class="status-pill status-approved">APPROVED</span>
              @elseif($st === 'completed')
                <span class="status-pill status-completed">COMPLETED</span>
              @elseif($st === 'cancelled')
                <span class="status-pill status-cancelled">CANCELLED</span>
              @else
                <span class="badge bg-dark">{{ strtoupper($st) }}</span>
              @endif
            </td>

            <td class="text-muted small">{{ $o->created_at?->format('Y-m-d H:i') }}</td>

            <td class="text-end">
              <div class="d-inline-flex gap-1 flex-wrap justify-content-end">

                {{-- VIEW BUTTON --}}
                <button
                  type="button"
                  class="btn btn-sm btn-outline-dark btn-soft"
                  data-bs-toggle="modal"
                  data-bs-target="#viewOrderModal{{ $o->id }}">
                  View
                </button>

                @if($st === 'pending')
                  <form method="POST" action="{{ route('farmer.orders.approve', $o->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-primary btn-soft"
                      onclick="return confirm('Approve this order?')">
                      Approve
                    </button>
                  </form>
                @endif

                @if(in_array($st, ['pending','approved'], true))
                  <form method="POST" action="{{ route('farmer.orders.complete', $o->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-success btn-soft"
                      onclick="return confirm('Mark this order as completed?')">
                      Complete
                    </button>
                  </form>
                @endif

                @if($st !== 'completed' && $st !== 'cancelled')
                  <form method="POST" action="{{ route('farmer.orders.cancel', $o->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger btn-soft"
                      onclick="return confirm('Cancel this order and return stock?')">
                      Cancel
                    </button>
                  </form>
                @endif

              </div>
            </td>
          </tr>

          {{-- VIEW ORDER MODAL --}}
          <div class="modal fade" id="viewOrderModal{{ $o->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content border-0 shadow">
                <div class="modal-header">
                  <h5 class="modal-title fw-bold">Order Details #{{ $o->id }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="modal-info-box">
                        <div class="d-flex gap-3 align-items-center mb-3">
                          @if($img)
                            <img src="{{ $img }}" class="img" alt="photo">
                          @else
                            <div class="img d-flex align-items-center justify-content-center text-muted small">No photo</div>
                          @endif
                          <div>
                            <div class="fw-bold">{{ $o->product?->name ?? '-' }}</div>
                            <div class="text-muted small text-uppercase">{{ $o->product?->type ?? '' }}</div>
                          </div>
                        </div>

                        <div class="row g-3">
                          <div class="col-6">
                            <div class="order-meta-label">Quantity</div>
                            <div class="order-meta-value">{{ number_format((float)$o->quantity_kilos, 2) }} kg</div>
                          </div>
                          <div class="col-6">
                            <div class="order-meta-label">Unit Price</div>
                            <div class="order-meta-value">₱{{ number_format((float)$o->unit_price, 2) }}</div>
                          </div>
                          <div class="col-6">
                            <div class="order-meta-label">Total Price</div>
                            <div class="order-meta-value text-success">₱{{ number_format((float)$o->total_price, 2) }}</div>
                          </div>
                          <div class="col-6">
                            <div class="order-meta-label">Status</div>
                            <div class="order-meta-value">{{ strtoupper($o->status ?? 'pending') }}</div>
                          </div>
                          <div class="col-12">
                            <div class="order-meta-label">Ordered At</div>
                            <div class="order-meta-value">{{ $o->created_at?->format('F d, Y h:i A') ?? '-' }}</div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="modal-info-box">
                        <h6 class="fw-bold mb-3">Resident Information</h6>

                        <div class="mb-2">
                          <div class="order-meta-label">Full Name</div>
                          <div class="order-meta-value">{{ $o->resident?->fullname ?? $o->resident?->username ?? '-' }}</div>
                        </div>

                        <div class="mb-2">
                          <div class="order-meta-label">Username</div>
                          <div class="order-meta-value">{{ $o->resident?->username ?? '-' }}</div>
                        </div>

                        <div class="mb-2">
                          <div class="order-meta-label">Email</div>
                          <div class="order-meta-value">{{ $o->resident?->email ?? '-' }}</div>
                        </div>

                        <div class="mb-2">
                          <div class="order-meta-label">Barangay</div>
                          <div class="order-meta-value">{{ $o->resident?->barangay ?? '-' }}</div>
                        </div>

                        <div class="mb-2">
                          <div class="order-meta-label">Delivery Address</div>
                          <div class="order-meta-value">{{ $o->delivery_address ?? 'No delivery address provided.' }}</div>
                        </div>

                        <div class="mb-2">
                          <div class="order-meta-label">Notes</div>
                          <div class="order-meta-value">{{ $o->notes ?? 'No notes provided.' }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  @if($st === 'pending')
                    <form method="POST" action="{{ route('farmer.orders.approve', $o->id) }}">
                      @csrf
                      <button class="btn btn-primary btn-soft"
                        onclick="return confirm('Approve this order?')">
                        Approve
                      </button>
                    </form>
                  @endif

                  @if(in_array($st, ['pending','approved'], true))
                    <form method="POST" action="{{ route('farmer.orders.complete', $o->id) }}">
                      @csrf
                      <button class="btn btn-success btn-soft"
                        onclick="return confirm('Mark this order as completed?')">
                        Complete
                      </button>
                    </form>
                  @endif

                  @if($st !== 'completed' && $st !== 'cancelled')
                    <form method="POST" action="{{ route('farmer.orders.cancel', $o->id) }}">
                      @csrf
                      <button class="btn btn-outline-danger btn-soft"
                        onclick="return confirm('Cancel this order and return stock?')">
                        Cancel
                      </button>
                    </form>
                  @endif

                  <button type="button" class="btn btn-secondary btn-soft" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

        @empty
          <tr>
            <td colspan="9" class="text-center text-muted py-4">No orders yet.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $orders->links() }}
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>