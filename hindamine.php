<?php include('config.php'); ?>
<!doctype html>
<html lang="et">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restoranide hindamine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
       *{
            margin: 0;
            padding: 0;
        }
        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
        }
        .rate:not(:checked) > input {
            position:absolute;
            top:-9999px;
        }
        .rate:not(:checked) > label {
            float:right;
            width:1em;
            overflow:hidden;
            white-space:nowrap;
            cursor:pointer;
            font-size:30px;
            color:#ccc;
        }
        .rate:not(:checked) > label:before {
            content: '★ ';
        }
        .rate > input:checked ~ label {
            color: #43d037;    
        }
        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label {
            color: #43c037;  
        }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label {
            color: #43d000;
        }
    </style>
</head>
  <body>
  <div class="container">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
    <?php
    // Hinnangu kustutamine
        if (!empty($_GET["del"])) {
            $del = $_GET["del"];
            $id = $_GET["id"];
            $paring = 'DELETE FROM hinnangud WHERE id=' . $del;
            $valjund = mysqli_query($yhendus, $paring);
            header('Location: asutused.php?id=' . $id);
        }

    // Hinnangu lisamine
        if (!empty($_GET["nimi"]) && !empty($_GET["kommentaar"]) && !empty($_GET["rate"])) {
            $nimi = $_GET["nimi"];
            $kommentaar = $_GET["kommentaar"];
            $rate = $_GET["rate"];
            $id = $_GET["id"];
            $paring = 'INSERT INTO hinnangud (nimi, kommentaar, hinnang, asutused_id) VALUES ("' . $nimi . '", "' . $kommentaar . '", ' . $rate . ', ' . $id . ')';
            $valjund = mysqli_query($yhendus, $paring);

            // Hindajate arvu ja keskmise hinde uuendamine
            $hindajate_arv_paring = "SELECT hinnatud, keskmine_hinne FROM asutused WHERE id=" . $id;
            $hindajate_arv_valjund = mysqli_query($yhendus, $hindajate_arv_paring);
            $asutus = mysqli_fetch_assoc($hindajate_arv_valjund);

            $hindajate_arv = $asutus['hinnatud'];
            $olemasolev_keskmine = $asutus['keskmine_hinne'];

            $uus_hindajate_arv = $hindajate_arv + 1;
            $uus_keskmine = round((($olemasolev_keskmine * $hindajate_arv) + $rate) / $uus_hindajate_arv, 2);

            $paring = 'UPDATE asutused SET hinnatud = ' . $uus_hindajate_arv . ', keskmine_hinne = ' . $uus_keskmine . ' WHERE id=' . $id;
            $valjund = mysqli_query($yhendus, $paring);
            header('Location: hindamine.php?id=' . $id);
        }

    // Hinnangute kuvamine
        if (!empty($_GET["id"])) {
            $id = $_GET["id"];
            $paring = 'SELECT * FROM asutused WHERE id=' . $id;
            $valjund = mysqli_query($yhendus, $paring);
            $ettevotte_nimi = mysqli_fetch_assoc($valjund);
        } else {
            header('Location: index.php');
        }
    ?>
      <h1>Hinda restorani <strong><?php echo $ettevotte_nimi['nimi'];  ?></strong></h1>
    <form action="" method="get">
        <div class="row">
            <div class="col-sm-4">Nimi:</div>
            <div class="col-sm-8"><input required type="text" name="nimi"></div>
        </div>
        <div class="row">
            <div class="col-sm-4">Kommentaar:</div>
            <div class="col-sm-8"><textarea required name="kommentaar" rows="4" cols="50"></textarea></div>
        </div>
        <div class="row">
            <div class="col-sm-4">Hinnang:</div>
            <div class="col-sm-8">
                <div class="rate">
                    <?php for ($i = 10; $i >= 1; $i--) : ?>
                        <input type="radio" id="star<?php echo $i; ?>" name="rate" value="<?php echo $i; ?>" required/>
                        <label for="star<?php echo $i; ?>"><?php echo $i; ?> stars</label>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><input class="btn btn-danger" type="submit" value="Hinda"></div>
    </form>
    <div class="row">
            <div class="col-sm-4"> <a class="btn btn-primary btn-sm" href="index.php">Tagasi</a></div>
    </div>

    <table class="table table-sm">
          <tr>
                <th>Nimi</th>
                <th>Kommentaar</th>
                <th>Hinnang</th>
          </tr>
          <?php
                $paring = 'SELECT hinnangud.id as hinnangud_id, asutused.nimi as ettevotte_nimi, hinnangud.kommentaar, hinnangud.hinnang, hinnangud.asutused_id 
                FROM asutused
                INNER JOIN hinnangud ON hinnangud.asutused_id=asutused.id
                WHERE asutused_id=' . $id;
                $valjund = mysqli_query($yhendus, $paring);
                while ($rida = mysqli_fetch_assoc($valjund)) {
                 echo '<tr>';
                 echo '<td>' . $rida['hindaja_nimi'] . '</td>';
                 echo '<td>' . $rida['kommentaar'] . '</td>';
                 echo '<td>' . $rida['hinnang'] . '/10</td>';
                 echo '<td><a href="hinnangud.php?del=' . $rida['hinnangud_id'] . '&id='.$id.'"><span class="badge text-bg-danger">x</span></a></td>';
                 echo '</tr>';
                }
          ?>
          </table>
</div>
            <div class="col-2"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>