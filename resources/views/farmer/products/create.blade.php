<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Post Rice Product | ANI-CARE</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    background:#eef3f8;
    font-family:'Segoe UI',sans-serif;
    color:#333;

}

.container{

    max-width:760px;

}

/*===============================
CARD
================================*/

.card{

    border:none;
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 12px 30px rgba(0,0,0,.08);

}

.card-body{

    padding:35px;

}

/*===============================
HEADER
================================*/

.page-header{

    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:30px;
    flex-wrap:wrap;

}

.page-title{

    font-size:38px;
    font-weight:700;
    color:#198754;
    margin-bottom:5px;

}

.page-subtitle{

    color:#6c757d;
    font-size:17px;

}

/*===============================
FORM
================================*/

.form-label{

    font-weight:600;
    color:#198754;
    margin-bottom:8px;

}

.form-control,
.form-select{

    height:56px;
    border-radius:12px;

}

.form-control:focus,
.form-select:focus{

    border-color:#198754;
    box-shadow:0 0 0 .25rem rgba(25,135,84,.15);

}

textarea.form-control{

    min-height:120px;

}

/*===============================
FILE INPUT
================================*/

.form-control[type=file]{

    padding:14px;

}

/*===============================
BUTTONS
================================*/

.btn{

    border-radius:12px;

}

.btn-success{

    background:#198754;
    border:none;

}

.btn-success:hover{

    background:#157347;

}

.submit-btn{

    width:100%;
    padding:15px;
    font-size:18px;
    font-weight:600;

}

/*===============================
ALERTS
================================*/

.alert{

    border:none;
    border-radius:14px;

}

/*===============================
IMAGE PREVIEW
================================*/

.preview-wrapper{

    display:none;
    margin-top:20px;
    text-align:center;

}

.preview-wrapper img{

    width:100%;
    max-height:300px;
    object-fit:cover;
    border-radius:15px;
    border:1px solid #ddd;

}

/*===============================
MOBILE
================================*/

@media(max-width:768px){

.page-title{

    font-size:30px;

}

.page-header{

    flex-direction:column;
    align-items:flex-start;

}

.page-header .btn{

    width:100%;

}

.card-body{

    padding:25px;

}

}

@media(max-width:576px){

.page-title{

    font-size:28px;

}

.page-subtitle{

    font-size:15px;

}

.card-body{

    padding:20px;

}

}

</style>

</head>

<body>

<div class="container py-4">

<div class="page-header">

<div>

<h1 class="page-title">

🌾 Post Rice Product

</h1>

<div class="page-subtitle">

Add your rice or palay product with price, available stocks, and photo.

</div>

</div>

<a href="{{ route('farmer.dashboard') }}"
   class="btn btn-outline-success">

<i class="bi bi-arrow-left"></i>

Back to Dashboard

</a>

</div>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

@if($errors->any())

<div class="alert alert-danger">

<ul class="mb-0">

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<div class="card">

<div class="card-body">

<form method="POST"
      action="{{ route('farmer.products.store') }}"
      enctype="multipart/form-data">

@csrf{{-- PRODUCT NAME --}}

<div class="mb-4">

    <label class="form-label">

        <i class="bi bi-box-seam"></i>

        Product Name

    </label>

    <input
        type="text"
        name="name"
        class="form-control"
        placeholder="Example: Premium Jasmine Rice"
        value="{{ old('name') }}"
        required>

</div>


<div class="row g-4">

    {{-- TYPE --}}

    <div class="col-md-6">

        <label class="form-label">

            <i class="bi bi-grid"></i>

            Product Type

        </label>

        <select
            name="type"
            class="form-select"
            required>

            <option value="">Select Product Type</option>

            <option value="rice"
                {{ old('type')=='rice' ? 'selected' : '' }}>

                🌾 Rice

            </option>

            <option value="palay"
                {{ old('type')=='palay' ? 'selected' : '' }}>

                🌱 Palay

            </option>

        </select>

    </div>

    {{-- PRICE --}}

    <div class="col-md-6">

        <label class="form-label">

            <i class="bi bi-cash-stack"></i>

            Price per Kilogram

        </label>

        <div class="input-group">

            <span class="input-group-text">

                ₱

            </span>

            <input
                type="number"
                step="0.01"
                min="0"
                name="price_per_kg"
                class="form-control"
                placeholder="0.00"
                value="{{ old('price_per_kg') }}"
                required>

        </div>

    </div>

    {{-- STOCKS --}}

    <div class="col-md-6">

        <label class="form-label">

            <i class="bi bi-basket"></i>

            Available Stocks (kg)

        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="kilos_available"
            class="form-control"
            placeholder="Enter available kilograms"
            value="{{ old('kilos_available') }}"
            required>

    </div>

    {{-- PHOTO --}}

    <div class="col-md-6">

        <label class="form-label">

            <i class="bi bi-image"></i>

            Product Photo (Optional)

        </label>

        <input
            type="file"
            name="photo"
            id="photo"
            class="form-control"
            accept=".jpg,.jpeg,.png">

        <div class="form-text">

            JPG, JPEG or PNG • Maximum 2 MB

        </div>

    </div>

</div>

{{-- IMAGE PREVIEW --}}

<div
    class="preview-wrapper"
    id="previewWrapper">

    <h6 class="mt-4 mb-3 text-success">

        Photo Preview

    </h6>

    <img
        id="previewImage"
        src=""
        alt="Preview">

</div>

<hr class="my-4">

<button
    type="submit"
    class="btn btn-success submit-btn"
    id="submitBtn">

    <i class="bi bi-upload"></i>

    Post Product

</button>
</form>

</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const photoInput = document.getElementById("photo");
    const previewWrapper = document.getElementById("previewWrapper");
    const previewImage = document.getElementById("previewImage");
    const form = document.querySelector("form");
    const submitBtn = document.getElementById("submitBtn");

    /*==========================
      IMAGE PREVIEW
    ==========================*/

    photoInput.addEventListener("change", function(e){

        const file = e.target.files[0];

        if(file){

            const reader = new FileReader();

            reader.onload = function(event){

                previewImage.src = event.target.result;

                previewWrapper.style.display = "block";

            };

            reader.readAsDataURL(file);

        }else{

            previewWrapper.style.display = "none";

            previewImage.src = "";

        }

    });

    /*==========================
      SUBMIT LOADING
    ==========================*/

    form.addEventListener("submit", function(){

        submitBtn.disabled = true;

        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Posting Product...
        `;

    });

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>