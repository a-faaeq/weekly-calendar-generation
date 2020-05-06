<div class="table-center">

    <div id="hours">
        <?php
        $start = 480;

        for ($i = 0; $i < 45; $i++)
        {
            echo '<div class="hour">';
            echo '<span>';
            if ($start % 60 === 0) {
                echo intdiv($start, 60) . 'h00';
            }elseif ($start % 60 === 30) {
                echo intdiv($start, 60) . 'h30';
            }
            echo '</span>';
            echo '</div>';
            $start += 15;
        }
        ?>
    </div>
    <div id="monday" class="day">
        <div class="title" id="monday-title">Lundi</div>
        <div class="title" id="monday-filter-1">Filtre 1</div>
        <?php
        echo $edt->buildDay(
            $result,
            $options = [
                'cellClass' => 'cell',
                'seanceClass' => 'seance',
                'seanceTitleClass' => 'seance-title'
            ]);
        ?>
    </div>



</div>