
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Insight Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../superadmin/dashboard.php">Code Insight Academy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../superadmin/dashboard.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Branches
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../superadmin/branches.php">Branches</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../superadmin/trainers.php">Trainers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../superadmin/students.php">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../superadmin/courses.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../superadmin/task_sheet.php">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../superadmin/feedbacks.php">Feedback</a>
                    </li>
                </ul>
                <form class="d-flex ms-1" role="search" >
                    <input class="form-control me-4" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>

                <a href="../superadmin/logout.php"><button id="logout-button" class="logout-btn btn btn-danger ms-4 mt-1">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button></a>

            </div>
        </div>
    </nav>

    <!-- Bootstrap JS and dependencies (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</body>

</html>