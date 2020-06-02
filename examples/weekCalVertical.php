
<div class="table-vertical">
    <div>
        <?php

        $start = 480;

        echo '<span class="hourV"></span><span class="hourV"></span>';
        for ($i = 0; $i < 45; $i++)
        {
            echo '<span class="hourV">';
            if ($start % 60 === 0) {
                echo intdiv($start, 60) . 'h00';
            }elseif ($start % 60 === 30) {
                echo intdiv($start, 60) . 'h30';
            }
            echo '</span>';
            $start += 15;
        }

        ?>
    </div>

  <div id="monday" class="dayV">
        <span class="title-vertical" id="monday-title">Lundi</span>
        <span class="title-vertical" id="monday-filter-1">Filtre 1</span>
      <?php

      echo $edt->buildDayV(
              $result,
              $options = [
                  'cellClass' => 'cellV',
                  'seanceClass' => 'seanceV',
                  'seanceTitleClass' => 'seance-title'
              ]);

      ?>

  </div>

</div>