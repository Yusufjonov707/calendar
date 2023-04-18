<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Colendar</title>
    <link rel="stylesheet" href="smth.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="/js/jquery-3.5.1.js"></script>
</head>
<body>
  <?php
    $body_bg = "../onlyimg/spring.jpg";
    $seasons = [
        'winter' => [1, 2, 12],
        'spring' => [3, 4, 5],
        'summer' => [6, 7, 8],
        'fall' => [9, 10, 11]
    ];
    include_once "modal.php";
    include_once "modal2.php";
    $timezone = "Asia/Tashkent";
    $area = $_GET['area'] ?? $timezone;
    $month = $_GET['month'] ?? date('m');
    $year = $_GET['year'] ?? date('Y');
    $year = (int) $year;
    $month = (int) $month;
    if ($year < 1900 || $year > 2200) {
        $year = date('Y');
    }
    if ($month < 1 || $month > 12) {
        $month = date('m');
    }
    $season_name = null;

    if (in_array($month, $seasons['winter'])) {
        $season_name = 'winter';
        $body_bg = "../onlyimg/winter.jpg";
    };
    if (in_array($month, $seasons['spring'])) {
        $season_name = 'spring';
        $body_bg = "../onlyimg/spring.jpg";
    };
    if (in_array($month, $seasons['summer'])) {
        $season_name = 'summer';
        $body_bg = "../onlyimg/summer.jpg";
    };
    if (in_array($month, $seasons['fall'])) {
        $season_name = 'fall';
        $body_bg = "../onlyimg/fall.jpg";
    };
    #############################Database##########################
    require_once 'database.php';
    $conn = new \Database\database('myitedu');
    if ($month < 10) {
        $mymonth = "0" . $month;
        $mymonth = "0" . $month;
    }
    $sql = "SELECT * FROM events WHERE event_date LIKE '%$year-$mymonth%';;";
    $events = $conn->sql($sql);
    ###############################################################

    $today_date = date('d');
    $last_day = date('t', strtotime("$month/$today_date/$year"));
    $month_name = date('F', strtotime("$month/$today_date/$year"));
    $first_weekday_number = date('w', strtotime("$month/1/$year"));
    $holidays['Uzbekistan'] = [
        1 => [
            1 => ['New Year'],
        ],
        2 => [
            14 => ['Qurolli Kuchlar Kuni'],
        ],
        3 => [
            8 => ['International Womens day'],
            21 => ['My sister\'s birthday', 'Navzur'],
        ],
        4 => [
            1 => ['My Birthday']
        ],
        5 => [
            9 => ['Remembrance Day', 'Victory Day'],
            12 => ['Eid al-Fitr']
        ],
        7 => [
            19 => ['Eid al-Adha']
        ],
        9 => [
            1 => ['Independence Day of Uzbekistan']
        ],
        10 => [
            1 => ['Teachers Day']
        ],
        12 => [
            8 => ['Constitution Day']
        ]
    ];
    $days = [];
    $event_titles = [];
    foreach ($events as $event) {
        $date = date('d', strtotime($event['event_date']));
        $date = (int) $date;
        $days[$date][] = $event;
        $event_titles[$date][] = $event['title'] ?? null;
    }
    ?>
    <div id="colendar" data-month="<?php echo $month; ?>" data-year="<?php echo $year ?>">
        <div id="calendar_header">

            <form>
                <p>Select:
                    <select name="month">
                        <?php for ($m = 1; $m <= 12; $m++) {
                            if ($m == $month) {
                                echo "<option selected value = \"$m\">" . date('F', strtotime("$m/1/$year")) . "</option >";
                            } else {
                                echo "<option value = \"$m\">" . date('F', strtotime("$m/1/$year")) . "</option >";
                            }
                        }
                        ?>
                    </select>
                    <input name="year" type="number" placeholder="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </p>
                <p> Country: <input type="text" value="<?php echo $area ?>" name="area" placeholder="Name of that country"></input></p>
            </form>

        </div>

        <table class="table table-bordered">
            <tr>
                <th colspan="7"><?php echo $month_name . " " . $year; ?></th>
            </tr>
            <?php
            $rows = range(1, 6);
            $cols = range(0, 6);
            $counter = 0;
            $counter2 = 1;
            $today_class_name =  "class = 'active_days'";
            foreach ($rows as $row) {

                if ($row == 1) {
                    echo "<th>S</th>";
                    echo "<th>M</th>";
                    echo "<th>T</th>";
                    echo "<th>W</th>";
                    echo "<th>T</th>";
                    echo "<th>F</th>";
                    echo "<th>S</th>";
                }

                echo "<tr>";

                foreach ($cols as $col) {
                    if ($counter > $first_weekday_number) {
                        $counter2++;
                    }
                    if ($month == date('m') && $year == date('Y') && $counter2 == date('d')) {
                        $today_class_name = "class='today_class'";
                    } else {
                        $today_class_name = "class = 'active_days'";
                    }
                    if ($counter < $first_weekday_number || $counter2 > $last_day) {
                        echo "<td>&nbsp;</td >";
                    } else {
                        $total_events = $holidays['Uzbeksitan'][$month][$counter2] ?? [];





                        if (isset($total_events)) {
                            $total_events2 = $days[$counter2] ?? [];
                            $show_holiday = count($total_events) + count($total_events2);
                        } else {
                            $show_holiday = 0;
                        }
                        if ($show_holiday) {
                            echo "<td $today_class_name><div  data-clicked='no' data-month='$month' data-year='$year' data-date='$counter2'  class='today_events'>$show_holiday</div>$counter2</td >";
                        } else {
                            echo "<td $today_class_name></div>$counter2</td >";
                        }
                    }
                    $counter++;
                }
                echo "</tr >";
            }
            ?>

        </table>
        <div id="calendar_footer">
            <ul>
                <?php
                $allevents = [];
                $myholidays = $holidays['Uzbeksitan'][$month] ?? [];

                if ($myholidays) {
                    $allevents = array_merge($holidays['Uzbekistan'][$month], $event_titles);
                } else {
                    $allevents = $event_titles;
                }

                foreach ($allevents as $days) {
                    foreach ($days as $day) {
                        echo "<li>$day</li>";
                    }
                }

                ?>

            </ul>
        </div>
    </div>
    <div class="time_table">
        <label class="Time_label">Time in <?php echo $area ?></label><br><br
        <?php date_default_timezone_set($area);
        echo date('h:i:sa'); ?>
    </div>
    <style>
        body {
            background-image: url("<?php echo $body_bg ?>");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        td {
            text-align: center;
            font-size: 30px;
            font-weight: border;
            background-color: darkseagreen;
        }

        .today_class {
            background-color: lightseagreen;
            color: black;
            font-weight: 600;
        }
    </style>
    <script>
        $(function() {

            var clicked_today_event = false;

            $(".active_days").click(function() {
                var day = $(this).text();
                var month = $("#calendar").data("month");
                var year = $("#calendar").data("year");
                var clicked = $(this).children('.today_events').data('clicked');
                $("#calendar_modal").modal("hide");


                if (typeof clicked == 'undefined' && clicked != 'no') {
                    $("#calendar_modal").modal("show");
                }

                console.error(clicked);


                if (month < 9) {
                    month = '0' + month;
                }
                if (day < 10) {
                    day = '0' + day;
                }
                var display_date = year + '-' + month + '-' + day;
                $("#calendar_modal_day").val(display_date);
                // $(this).children('.today_events').data('clicked','no');
            });

            $(document).on("click", "#btn_save_events", function() {
                var calendar_modal_title = $("#calendar_modal_title").val();
                var calendar_modal_day = $("#calendar_modal_day").val();
                var calendar_modal_time = $("#calendar_modal_time").val();
                if (calendar_modal_title.length < 3) {
                    alert("Your event title is too short");
                    return false;
                }
                if (calendar_modal_day.length < 3) {
                    alert("You must enter correct date");
                    return false;
                }
                if (calendar_modal_time.length < 3) {
                    alert("You must enter correct time");
                    return false;
                }

                var parms = {
                    'event_title': calendar_modal_title,
                    'event_day': calendar_modal_day,
                    'event_time': calendar_modal_time,
                    'action': 'create'
                };
                var api = $.post('backend.php', parms, function(response) {
                    document.location = '/korea/bahodir/newprojects';
                });

            });

            $(document).on("click", ".today_events", function() {
                clicked_today_event = true;
                $("#calendar_modal").modal("hide");
                var m = $(this).data('month');
                var d = $(this).data('date');
                var y = $(this).data('year');

                var clicked = $(this).data('clicked');
                var parms = {
                    'm': m,
                    'd': d,
                    'y': y,
                    'action': 'fetch'
                };
                var api = $.post('backend.php', parms, function(response) {
                    $("#calendar_modal2").modal("show");
                    $(".modal-body2").html(response);
                });
                $(this).data('clicked', 'no');
                //$("#calendar_modal2").modal("show");

            });
        });
    </script>
</body>

</html>