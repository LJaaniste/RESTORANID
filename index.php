<?php include('config.php'); ?>
<!doctype html>
<html lang="et">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restoranide hindamine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRrST7a8J5yL6P1lAoXfD8DymxaCmr6On6fT8Dz+r" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>  

<style>
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
    <?php
        #$sort_by = $_GET['sort'] ?? 'nimi';
        #$sort_order = $_GET['order'] ?? 'ASC';

        if (!empty($_GET["s"])) {
            $s = $_GET["s"];
            #$paring = "SELECT * FROM asutused WHERE nimi LIKE '%$s%' ORDER BY $sort_by $sort_order";
            $paring = 'SELECT * FROM asutused WHERE nimi LIKE "%' . $s . '%" ';
            #$paring = 'SELECT a.id, a.nimi, a.asukoht, AVG(h.hinnang) as keskmine_hinne, COUNT(h.id) as hinnatud 
                       #FROM asutused a 
                       #LEFT JOIN hinnangud h ON a.id = h.asutused_id 
                       #WHERE a.nimi LIKE "%' . $s . '%" 
                       #GROUP BY a.id';
        } else {
            #$algus = $_GET['next'] ?? 0;
            #if ($algus < 0) $algus = 0:
            $algus = 0;

            if (isset($_GET['next'])) {
                $algus = $_GET['next'];
            } else if (isset($_GET['prev'])) {
                $algus = $_GET['prev'] - 10;
            }

            if ($algus < 0) $algus = 0; 

           
            $paring = "SELECT * FROM asutused LIMIT $algus,10";
            #$paring = "SELECT a.id, a.nimi, a.asukoht, AVG(h.hinnang) as keskmine_hinne, COUNT(h.id) as hinnatud 
                       #FROM asutused a 
                       #LEFT JOIN hinnangud h ON a.id = h.asutused_id 
                       #GROUP BY a.id 
                       #LIMIT $algus,10";

            $asutused_kokku_paring = mysqli_query($yhendus, "SELECT COUNT(*) as kokku FROM asutused");
            $asutused_kokku = mysqli_fetch_assoc($asutused_kokku_paring)['kokku'];

            $next = $algus + 10;
            $prev = $algus - 10;

            if ($prev < 0) $prev = 0;
            if ($next >= $asutused_kokku) $next = $asutused_kokku - ($asutused_kokku % 10);
        }

        $valjund = mysqli_query($yhendus, $paring);
    ?>
    <div class="container">
    <div class="row">
            <div class="col-6 text-end">
            
        <h1>Valige restoran, mida hinnata</h1>
        </div>
        <div class="row">
            <div class="col-9">
            </div>
            <div class="col-3 text-end">
            <form class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="s" placeholder="Otsi asutust" value="<?php if (!empty($_GET["s"])) echo $_GET["s"]; ?>">
                <button class="btn btn-primary" type="submit">Otsi</button>
            </div>
        </form>
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
        while($rida = mysqli_fetch_assoc($valjund)){
        ?>
            <tr>
                <td><a href="hindamine.php?id=<?php echo $rida['id']; ?>"><?php echo $rida['nimi']; ?></a></td>
                <td><?php echo $rida['asukoht']; ?></td>
                <td><?php echo round($rida['keskmine_hinne'], 1); ?></td>
                <td><?php echo $rida['hinnatud_korda']; ?></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        </table>
<br>
        <div class="d-flex justify-content-end">
            <a href="?prev=<?php echo $prev; ?>" class="btn btn-primary <?php if ($algus == 0) echo 'disabled'; ?>" role="button" aria-disabled="<?php if ($algus == 0) echo 'true'; ?>">&lt;&lt; Eelmised</a>
            <a href="?next=<?php echo $next; ?>" class="btn btn-primary ms-2 <?php if ($algus + 10 >= $asutused_kokku) echo 'disabled'; ?>" role="button" aria-disabled="<?php if ($algus + 10 >= $asutused_kokku) echo 'true'; ?>">Järgmised &gt;&gt;</a>
        </div> 
        
    </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>