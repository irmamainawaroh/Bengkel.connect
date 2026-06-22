<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="card p-4">

        <h2>Register Customer</h2>

        <form action="/register" method="POST">

            @csrf

            <input type="text"
                   name="nama"
                   class="form-control mb-3"
                   placeholder="Nama">

            <input type="email"
                   name="email"
                   class="form-control mb-3"
                   placeholder="Email">

            <input type="text"
                   name="no_hp"
                   class="form-control mb-3"
                   placeholder="No HP">

            <input type="text"
                   name="plat_nomor"
                   class="form-control mb-3"
                   placeholder="Plat Nomor">

            <input type="password"
                   name="password"
                   class="form-control mb-3"
                   placeholder="Password">

            <button class="btn btn-danger">
                Register
            </button>

        </form>

    </div>

</div>

</body>
</html>