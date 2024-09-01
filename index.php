<?php include("config.php"); ?>

<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restoranide hindamine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>  
    
    <style>
        /* Stiilid siia */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        th a {
            text-decoration: none;
            color: black;
        }
        th::after {
            content: '';
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            position: absolute;
            right: 5px;
            top: 50%;
            transition: 0.3s;
        }
        .asc::after {
            border-bottom: 6px solid #000;
        }
        .desc::after {
            border-top: 6px solid #000;
        }
    </style>
</head>

<body>

<header>
    <h1>Valige asutus, mida hinnata</h1>
</header>
<br>

<div class="container">
    <div class="search">
        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <form method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="s" placeholder="Otsi asutust" value="<?php echo htmlspecialchars($_GET['s'] ?? ''); ?>">
                        <button class="btn btn-primary" type="submit">Otsi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <main>
        <br>
        <table border="1" id="restaurantTable">
            <thead>
                <tr>
                    <?php 
                    $sort_by = $_GET['sort'] ?? 'nimi';
                    $sort_order = $_GET['order'] ?? 'ASC';
                    $page = $_GET['page'] ?? 1;
                    $search_term = $_GET['s'] ?? '';

                    function generateSortLink($column, $sort_by, $sort_order, $search_term, $page) {
                        $new_order = ($sort_by == $column && $sort_order == 'ASC') ? 'DESC' : 'ASC';
                        return "?sort=$column&order=$new_order&s=$search_term&page=$page";
                    }
                    ?>
                    <th>
                        <a href="<?php echo generateSortLink('nimi', $sort_by, $sort_order, $search_term, $page); ?>">
                            Nimi <?php if ($sort_by == 'nimi') echo $sort_order == 'ASC' ? '▲' : '▼'; else echo '▲▼'; ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?php echo generateSortLink('asukoht', $sort_by, $sort_order, $search_term, $page); ?>">
                            Asukoht <?php if ($sort_by == 'asukoht') echo $sort_order == 'ASC' ? '▲' : '▼'; else echo '▲▼'; ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?php echo generateSortLink('keskmine_hinne', $sort_by, $sort_order, $search_term, $page); ?>">
                            Keskmine hinne <?php if ($sort_by == 'keskmine_hinne') echo $sort_order == 'ASC' ? '▲' : '▼'; else echo '▲▼'; ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?php echo generateSortLink('hinnatud_korda', $sort_by, $sort_order, $search_term, $page); ?>">
                            Hinnatud (korda) <?php if ($sort_by == 'hinnatud_korda') echo $sort_order == 'ASC' ? '▲' : '▼'; else echo '▲▼'; ?>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                function fetchRestaurants($page, $sort_by, $sort_order, $search_term) {
                    global $yhendus;
                    $offset = ($page - 1) * 10; // Arvutame offseti
                    $query = "SELECT * FROM asutused WHERE nimi LIKE '%$search_term%' OR asukoht LIKE '%$search_term%' ORDER BY $sort_by $sort_order LIMIT 10 OFFSET $offset";
                    $result = mysqli_query($yhendus, $query);
                    return $result;
                }

                $result = fetchRestaurants($page, $sort_by, $sort_order, $search_term);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td><a href='#' onclick='openRatingForm(" . $row['id'] . ", \"" . $row['nimi'] . "\")'>" . $row['nimi'] . "</a></td>";
                        echo "<td>" . $row['asukoht'] . "</td>";
                        echo "<td>" . $row['keskmine_hinne'] . "</td>";
                        echo "<td>" . $row['hinnatud_korda'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Andmeid ei leitud</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                    <div class="lk vahetus">
                        <?php if ($page > 1): ?>
                            <a href="?sort=<?php echo $sort_by ?>&order=<?php echo $sort_order ?>&s=<?php echo $search_term ?>&page=<?php echo $page - 1 ?>"> < Eelmine</a>
                        <?php endif; ?>
                        <?php if (mysqli_num_rows($result) == 10): ?>
                            <a href="?sort=<?php echo $sort_by ?>&order=<?php echo $sort_order ?>&s=<?php echo $search_term ?>&page=<?php echo $page + 1 ?>">Järgmine ></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </main>

    

    

    <script>
       function openRatingForm(id, name) {
            document.getElementById('restaurantName').innerText = name;
            document.getElementById('ratingForm').style.display = 'block';   
        }
        </script>
    <script>
        function setRating(rating) {
            let stars = document.querySelectorAll('.rating-stars span');
            for (let i = 0; i < stars.length; i++) {
                if (i < rating) {
                    stars[i].classList.add('active');
                } else {
                    stars[i].classList.remove('active');
                }
            }
        }
        </script>
        <script>
        // Funktsioon, mis muudab sorteerimissuunda ja värvi märki päises
        document.querySelectorAll('.sortable').forEach(function(header) {
            header.addEventListener('click', function() {
                let currentSortOrder = 'ASC';
                if (this.classList.contains('asc')) {
                    currentSortOrder = 'DESC';
                }
                window.location.href = '?sort=' + this.dataset.sort + '&order=' + currentSortOrder;
            });
        });
        </script>
        <script>
        function searchRestaurants() {
            let searchValue = document.getElementById('searchInput').value;
            window.location.href = '?search=' + searchValue;
        }

       
    </script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="https://unpkg.com/bootstrap-icons/font/bootstrap-icons.css"></script>
  </body>
</html>
