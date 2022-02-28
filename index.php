<?php 
  $string = file_get_contents('reviews.json');
  //echo var_dump($string);
  $string = preg_replace('/[[:^print:]]/', '', $string);
  $json_a = json_decode($string);
  //echo var_dump($json_a[0])."<br>";
  //echo var_dump($json_a[6]);
  //echo json_last_error_msg();
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    </head>
    <body>
      <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <div class="mb-3">
    <form action="" method="GET">
    <h2>Filter Reviews</h2>
    <label for="obr" class="form-label">Ordered by rating</label>
    <select id="obr" name="obr" class="form-control">
      <option value="hf">Higest first</option>
      <option value="lf">Lowest First</option>
    </select>
    <br>
    <label for="minrat" class="form-label">Minimum Rating</label>
    <select id="minrat" name="minrat" class="form-control">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
    </select>
    <br>
    <label for="obd" class="form-label">Ordered By Date</label>
    <select id="obd" name="obd" class="form-control">
      <option value="of">Oldest First</option>
      <option value="nf">Newest First</option>
    </select>
    <br>
    <label for="pt" class="form-label">Prioritize by Text</label>
    <select id="pt" name="pt" class="form-control">
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
    <br>
    <input type="submit" value="Filter" class="btn btn-primary">
    </form>
    </div>
    <table class="table">
      <?php 
      for($i=0;$i<count($json_a);$i++){
      if($json_a[$i]->reviewText){
        $json_at1[]=$json_a[$i];
      }else{
        $json_at2[]=$json_a[$i];
      }
    }
    //echo var_dump($json_at2);
      function OldestFirst($a,$b){
        if($a->reviewCreatedOnTime==$b->reviewCreatedOnTime) return 0;
        return($a->reviewCreatedOnTime<$b->reviewCreatedOnTime)?-1:1;
      }
      usort($json_at1,"OldestFirst");
      if($_GET['obd']=='nf'){
        $json_at1 = array_reverse($json_at1);
      }
      for($i=0;$i<count($json_at1);$i++){
        switch($json_at1[$i]->rating){
          case 1: $json_f1[]=$json_at1[$i];
          break;
          case 2: $json_f2[]=$json_at1[$i];
          break;
          case 3: $json_f3[]=$json_at1[$i];
          break;
          case 4: $json_f4[]=$json_at1[$i];
          break;
          case 5 : $json_f5[]=$json_at1[$i];
        }
      }
      $json_at1 = array_merge($json_f5,$json_f4,$json_f3,$json_f2,$json_f1);
      if($_GET['obr']=="lf"){
        $json_at1 = array_reverse($json_at1);
      }

      //
      usort($json_at2,"OldestFirst");
      if($_GET['obd']=='nf'){
        $json_at2 = array_reverse($json_at2);
      }
      for($i=0;$i<count($json_at2);$i++){
        switch($json_at2[$i]->rating){
          case 1: $json_f12[]=$json_at2[$i];
          break;
          case 2: $json_f22[]=$json_at2[$i];
          break;
          case 3: $json_f32[]=$json_at2[$i];
          break;
          case 4: $json_f42[]=$json_at2[$i];
          break;
          case 5 : $json_f52[]=$json_at2[$i];
        }
      }
      $json_at2 = array_merge($json_f52,$json_f42,$json_f32,$json_f22,$json_f12);
      if($_GET['obr']=="lf"){
        $json_at2 = array_reverse($json_at2);
      }

      //
      //echo var_dump($json_at2);
      if($_GET['pt']=="no"){
        $json_a = array_merge($json_at2,$json_at1);
      }else{
      $json_a = array_merge($json_at1,$json_at2);
      }
      //var_dump($json_a);
      for($i=0;$i<count($json_a);$i++){
        if($json_a[$i]->rating>=(int)$_GET['minrat']){
          $json_f[]=$json_a[$i];
        }}
        //var_dump((int)$_GET['minrat'])
        ?>
      <?php for($i=0; $i<count($json_f);$i++){?>
      <tr><td><?php echo $json_f[$i]->rating."-star rewiews";?></td>
      <td><?php if($json_f[$i]->reviewText){
        echo "With Text";}else{
        echo "Without Text";}?></td>
      <td><?php echo $json_f[$i]->reviewCreatedOnDate?></td></tr>
      <?php }?>
    </table>
    </body>
</html>
