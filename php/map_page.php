<?php
	require('../config.php');
	require('../functions.php');
	$pdo = ConnectDB();
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Brabant2Go</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/main.css">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	</head>

	<body>
    <span class="goBack" onclick="goBack()">Back</span>
    <script src="../js/main.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }
        $("#submit").on("click" , function(e){
            console.log();
            e.preventDefault();
            var sector = $(".active").attr("data-value");
            $(".query_results").html("");
            $.ajax({
                url: "functieDaan.php", //the page containing php script
                type: 'post',
                dataType: 'json',
                data: {sector: sector},
                success: function (data) {
                    console.log(data);
                },
                error: function(){
                    console.log('ajax 2  Failed ' + sector);
                }
            });
        });


    </script>
		<div>
                <div class="nb-s">
                    <div class="drop">
                        <div class="option active placeholder" data-value="placeholder">
                            Choose a business field!
                        </div>
                        <?php
                            $sth = $pdo->prepare("SELECT * FROM bedrijfsectors");
                            $sth->execute();

                            while($row = $sth->fetch()){
                        ?>
                            <div class="option" data-value="<?= $row["sectorNaam"]?>">
                                <?= $row["sectorNaam"]?>
                            </div>
                        <?php
                            }
                        ?>
                        <button id="submit" class="nb-zoek" onClick="openGemeenteLijst()">search</button>
                    </div>
			</div>
			<div class="nb-map-wrap">
			<?php
				echo "<div class='nb-map-base'>";
				include("../media/Noord-Brabant_v2.svg");
				echo "</div>";
			?>
			</div>

            <?php
            include ("../fields/vergelijkenfield.php");
            ?>
			<script>
                // fumctie per gemeente
                function openGemeente(name, population,  surface, gemeente, bis_one, bis_two ,bis_three, bis_four, plus_one, plus_two, plus_three, plus_four) {
                    var g_plus_one = plus_one.toString();
                    var g_plus_two = plus_two.toString();
                    var g_plus_four = plus_four.toString();
                    var g_plus_three = plus_three.toString();
                    var g_bis_four = bis_four.toString();
                    var g_bis_three = bis_three.toString();
                    var g_bis_two = bis_two.toString();
                    var g_gemeente = gemeente.toString();
                    var g_bis_one = bis_one.toString();
                    var g_population = population.toString();
                    var g_surface = surface.toString();
                    var g_name = name.split(' ').join('_').toString();
                    var g_name_n = name.split('_').join(' ').toString();
                    var g_name2 = "'" + g_name + "'";
                    $(".cls-2").removeClass('g-active');
                    $("#" + g_name).children('.cls-2').addClass('g-active');
                    console.log(g_name);
                    $( function() {
                        $( "#dialog-g-" + g_name ).dialog({
                            draggable: false,
                            modal: true,
                            autoOpen: true,
                            closeOnEscape: false,
                            resizable: false
                        });
                        $(".ui-dialog-titlebar").hide();
                    } );
                    $('body').append(
                        '<div id="dialog-g-' + g_name + '" class="g-wrap hide">\n' +
                        '            <span class="l-clo" onClick="cloDialog_g(' + g_name2 + ')" style="right: 1px; top: 1px;">x</span>\n' +
                        '\t\t\t<div class="g-topleft">\n' +
                        '                <h1>' + g_name_n + '</h1>\n' +
                        '                <ul>\n' +
                        '                    <li>Population: <span>' + g_population + '</span></li>\n' +
                        '                    <li>Surface: <span>' + g_surface + '</span> km²</li>\n' +
                        '                    <li>Province: <span>' + g_gemeente + '</span></li>\n' +
                        '                </ul>\n' +
                        '            </div>\n' +
                        '            <div class="g-topright">\n' +
                        '                <table id="wrapper">\n' +
                        '                    <tr>\n' +
                        '                        <td><img src="../media/' + g_name + '.png"></td>\n' +
                        '                    </tr>\n' +
                        '                </table>\n' +
                        '            </div>\n' +
                        '            <div class="g-bottomleft">\n' +
                        '                <ul>\n' +
                        '                    <li>' + g_plus_one + '</li>\n' +
                        '                    <li>' + g_plus_two + '</li>\n' +
                        '                    <li>' + g_plus_three + '</li>\n' +
                        '                    <li>' + g_plus_four + '</li>\n' +
                        '                </ul>\n' +
                        '            </div>\n' +
                        '            <div class="g-bottomright">\n' +
                        '                <h2>Business fields</h2>\n' +
                        '                <ul>\n' +
                        '                    <li>Engineering & Computer Science <span class="right">' + g_bis_one + '</span></li>\n' +
                        '                    <li>Education <span class="right">' + g_bis_two + '</span></li>\n' +
                        '                    <li>Arts & Entertainment <span class="right">' + g_bis_three + '</span></li>\n' +
                        '                    <li>Communications <span class="right">' + g_bis_four + '</span></li>\n' +
                        '                </ul>\n' +
                        '            </div>\n' +
                        '\t\t</div>'
                    );
                    setTimeout(function(){
                        $("#dialog-g-" + g_name).parent('.ui-dialog').addClass('g-main');
                        $("#dialog-g-" + g_name).removeClass('hide');
                    }, 10);
                }
				// functie lijst gemeenten
				function openGemeenteLijst() {
                    var name = $('.active').data('value');
                    var datafilterArray = [];
                    var filterArray = [];
                    var gemeenteId = Math.floor(Date.now() / 100);

                    filterArray.push(gemeenteId);
                    filterArray[gemeenteId] = [];
                    var filter;
                    var filterId;


                    $(function () {
                        $("#dialog-gemeente-" + gemeenteId).dialog({
                            draggable: false,
                            modal: true,
                            autoOpen: true,
                            resizable: false,
                            show: {
                                effect: "slideDown",
                                duration: 400
                            }
                        });
                        $(".ui-dialog-titlebar").hide();
                    });
                    $('body').append(
                        '<div class="dialog-gemeente-lijst" id="dialog-gemeente-' + gemeenteId + '">\n' +
                        '\t\t\t<span class="l-min" onClick="minDialog(' + gemeenteId + ')">-</span>\n' +
                        '\t\t\t<span class="l-clo" onClick="cloDialog(' + gemeenteId + ')">x</span>\n' +
                        '\t\t\t<div class="l-filter">\n' +
                        '                <ul>\n' +
                        '                    <li>\n' +
                        '                        <label class="container" id="snelweg">Highway\n' +
                        '                            <input id="one'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">International School\n' +
                        '                            <input id="two'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">HBO School\n' +
                        '                            <input id="three'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                </ul>\n' +
                        '                <ul>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">MBO School\n' +
                        '                            <input id="four'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">University\n' +
                        '                            <input id="five'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">Train Station\n' +
                        '                            <input id="six'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                </ul>\n' +
                        '                <ul>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">Subway\n' +
                        '                            <input id="seven'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">Tram\n' +
                        '                            <input id="eight'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">Bus\n' +
                        '                            <input id="nine'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                </ul>\n' +
                        '                <ul>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">Housing\n' +
                        '                            <input id="ten'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                    <li>\n' +
                        '                        <label class="container">Parking\n' +
                        '                            <input id="eleven'+gemeenteId+'" type="checkbox" class="checkbox'+gemeenteId+'">\n' +
                        '                            <span class="checkmark"></span>\n' +
                        '                        </label>\n' +
                        '                    </li>\n' +
                        '                </ul>\n' +
                        '            </div>\n' +
                        '			 <div class="gemeente-lijst">\n' +
                        '            	<div class="filter_results_' + gemeenteId + '"></div>' +
                        '            </div>\n' +
                        '\t\t</div>'
                    );
                    $('body').append('<div class="rb-bol tooltip" onClick="openDialog(' + gemeenteId + ')" id="bol-' + gemeenteId + '"><span class="tooltiptext tooltip-left">' + name +'</span></div>');
                    setTimeout(function(){
                        $("#dialog-gemeente-" + gemeenteId).removeClass('hide');
                        $("#dialog-gemeente-" + gemeenteId).parent('.ui-dialog').addClass('lijst');
                    }, 100);

                            $.ajax({
                                url: "postfirstFilterResults.php",
                                type: "post",
                                dataType: "json",
                                data: {},
                                success: function (startData) {
                                    var i;
                                    console.log(startData);
                                    for(i=0; i<11; i++){
                                        $('.filter_results_'+ gemeenteId).append(
                                        '               <div class="gemeente-item">\n' +
                                        '                    <div class="gemeente-info">\n' +
                                        '                        <h1>'+startData[i][5]+'</h1>\n' +
                                        '                        <p>Population: <span>90.168</span></p>\n' +
                                        '                        <p>Surface: <span>170,6</span> km²</p>\n' +
                                        '                    </div>\n' +
                                        '                    <div class="gemeente-top">\n' +
                                        '                        <ul id="data_'+startData[i][4] + gemeenteId + '">\n' +
                                        '                           <li>' +  startData[i][0] + '</li>\n' +
                                        '                            <li>' + startData[i][1] + '</li>\n' +
                                        '                            <li>' + startData[i][2] + '</li>\n' +
                                        '                            <li class="gemeente-compare" id="compare-oss">\n' +
                                        '                                <label class="container">Compare\n' +
                                        '                                    <input id="gem_checkbox'+startData[i][4]+'" name="gemeente" value="'+startData[i][4]+'" type="checkbox">\n' +
                                        '                                    <span class="checkmark"></span>\n' +
                                        '                                </label>\n' +
                                        '                            </li>\n' +
                                        '                        </ul>\n' +
                                        '                    </div>\n' +
                                        '                    <div class="gemeente-pic'+startData[i][4]+ gemeenteId +'">\n' +
                                        '                   <img src="../media/'+ startData[i][3] +'" width="75px" height="75  px"> \n'+
                                        '                    </div>\n' +
                                        '                </div>\n');
                                    }

                                },
                                error: function () {
                                    console.log('getStartFilterResult.php')
                                }
                            });
                    $(".checkbox"+ gemeenteId).change(function () {
                        var i = 0;
                        // fill filer array whit data
                        filterId = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten','eleven'];
                        filter = {filter_one: false, filter_two: false, filter_three: false, filter_four: false, filter_five: false, filter_six: false, filter_seven: false, filter_eight: false, filter_nine: false, filter_ten: false, filter_eleven: false};
                        filterArray[gemeenteId].push(filterId);
                        filterArray[gemeenteId].push(filter);
                        console.log(filterArray[gemeenteId][0][i] + gemeenteId);
                        for (key in filterArray[gemeenteId][1]) {

                            if (document.getElementById(filterArray[gemeenteId][0][i] + gemeenteId).checked) {
                                filterArray[gemeenteId][1][key] = true;
                            }
                            else {
                                filterArray[gemeenteId][1][key] = false;
                            }
                            i++;
                        }

                        // print filter array


                        // Get filter data end fill div statements
                        if (filter !== null) {

                            $.ajax({
                                url: "filterfunctie.php", //the page containing php script
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    filter_one: JSON.stringify(filterArray[gemeenteId][1]['filter_one']),
                                    filter_two: JSON.stringify(filterArray[gemeenteId][1]['filter_two']),
                                    filter_three: JSON.stringify(filterArray[gemeenteId][1]['filter_three'])
                                    ,
                                    filter_four: JSON.stringify(filterArray[gemeenteId][1]['filter_four']),
                                    filter_five: JSON.stringify(filterArray[gemeenteId][1]['filter_five']),
                                    filter_six: JSON.stringify(filterArray[gemeenteId][1]['filter_six'])
                                    ,
                                    filter_seven: JSON.stringify(filterArray[gemeenteId][1]['filter_seven']),
                                    filter_eight: JSON.stringify(filterArray[gemeenteId][1]['filter_eight']),
                                    filter_nine: JSON.stringify(filterArray[gemeenteId][1]['filter_nine'])
                                    ,
                                    filter_ten: JSON.stringify(filterArray[gemeenteId][1]['filter_ten']),
                                    filter_eleven: JSON.stringify(filterArray[gemeenteId][1]['filter_eleven'])
                                },
                                success: function (data) {
                                    datafilterArray.push(gemeenteId);
                                    datafilterArray[gemeenteId] = [];
                                    datafilterArray[gemeenteId].push(data);
                                    console.log(datafilterArray);
                                    var y = data.length;
                                    var x = 0;
                                    function myLoop () {           //  create a loop function
                                        setTimeout(function () {    //  call a 3s setTimeout when the loop is called



                                            if (x === 0) {
                                                $('.filter_results_' + gemeenteId).html("");
                                            }
                                            $(".filter_results_" + gemeenteId).append(
                                                '               <div class="gemeente-item">\n' +
                                                '                    <div class="gemeente-info">\n' +
                                                '                        <h1>'+data[x]['naam']+'</h1>\n' +
                                                '                        <p>Population: <span>90.168</span></p>\n' +
                                                '                        <p>Surface: <span>170,6</span> km²</p>\n' +
                                                '                    </div>\n' +
                                                '                    <div class="gemeente-top">\n' +
                                                '                        <ul id="data_'+data[x]['ID'] + gemeenteId + '">\n' +
                                                '                        </ul>\n' +
                                                '                    </div>\n' +
                                                '                    <div class="gemeente-pic'+data[x]['ID']+ gemeenteId +'">\n' +
                                                '                    </div>\n' +
                                                '                </div>\n');

                                            $.ajax({
                                                 url: "postFilterResults.php",
                                                 type: "post",
                                                 dataType: "json",
                                                 data: {data: data[x]['ID'], name: data[x]['naam']},
                                                 success: function (data2) {
                                                     console.log(data2);
                                                     $("#data_" + data2[4]+gemeenteId).append(
                                                         '                           <li>' + data2[0] + '</li>\n' +
                                                         '                            <li>' + data2[1] + '</li>\n' +
                                                         '                            <li>' + data2[2] + '</li>\n' +
                                                         '                            <li class="gemeente-compare" id="compare-oss">\n' +
                                                         '                                <label class="container">Compare\n' +
                                                         '                                    <input id="gem_checkbox'+data2[4]+'" name="gemeente" data-name-value="'+data2[5]+'" value="'+data2[4]+'" type="checkbox">\n' +
                                                         '                                    <span class="checkmark"></span>\n' +
                                                         '                                </label>\n' +
                                                         '                            </li>\n');

                                                         $(".gemeente-pic" + data2[4]+ gemeenteId).append('<img src="../media/'+ data2[3] +'" width="75px" height="75  px">\n')
                                                     $("#gem_checkbox"+ data2[4]).change(function() {
                                                         checkboxCheck();
                                                         console.log(allVals);
                                                         if(allVals[0] !== undefined && allVals[1] !== undefined){
                                                             $(".map_page").fadeOut(0);
                                                             $(".vergelijkfield").fadeIn(0);
                                                             var gem1name = $(allVals[0]).attr('name-value');
                                                             var gem2name = $(allVals[1]).attr('name-value');
                                                             $("#gem1Name").html('Uden');
                                                             $("#gem2Name").html('Cuijk');

                                                             $.ajax({
                                                                 url: "getCompareValues.php",
                                                                 type: "post",
                                                                 dataType: "json",
                                                                 data: {gem1: allVals[0], gem2: allVals[1]},
                                                                 success: function (data3) {
                                                                     console.log(data3);
                                                                     minDialog(' + gemeenteId + ')
                                                                     // append grades
                                                                     $("#gem1-1").append(data3[0][0]);
                                                                     $("#gem1-2").append(data3[0][1] );
                                                                     $("#gem1-3").append(data3[0][2]);
                                                                     $("#gem1-4").append(data3[0][3]);
                                                                     $("#gem1-5").append(data3[0][4]);
                                                                     $("#gem1-6").append(data3[0][5]);
                                                                     $("#gem1-7").append(data3[0][6]);
                                                                     $("#gem1-8").append(data3[0][7]);
                                                                     $("#gem1-9").append(data3[0][8]);
                                                                     $("#gem1-10").append(data3[0][9]);
                                                                     $("#gem1-11").append(data3[0][10]);


                                                                     // append grades
                                                                     $("#gem2-1").append(data3[1][0]);
                                                                     $("#gem2-2").append(data3[1][1]);
                                                                     $("#gem2-3").append(data3[1][2]);
                                                                     $("#gem2-4").append(data3[1][3]);
                                                                     $("#gem2-5").append(data3[1][4]);
                                                                     $("#gem2-6").append(data3[1][5]);
                                                                     $("#gem2-7").append(data3[1][6]);
                                                                     $("#gem2-8").append(data3[1][7]);
                                                                     $("#gem2-9").append(data3[1][8]);
                                                                     $("#gem2-10").append(data3[1][9]);
                                                                     $("#gem2-11").append(data3[1][10]);


                                                                 },
                                                                 error: function () {
                                                                     console.log('did not work /:');
                                                                 }
                                                             });

                                                         }
                                                     });
                                                 },
                                                 error: function () {
                                                     console.log('error');
                                                 }
                                             });   //  your code here
                                            x++;                     //  increment the counter
                                            if (x < y) {            //  if the counter < 10, call the loop function
                                                myLoop();             //  ..  again which will trigger another
                                            }                        //  ..  setTimeout()
                                        }, 50)
                                    }
                                    myLoop();
                                },
                                error: function () {
                                    console.log('ajax 2  Failed ' + filter);
                                }
                            });
                        }
                    });

                }
				function openDialog(id) {
                    var idN = "#dialog-gemeente-" + id;
                    $('.dialog-gemeente-lijst').dialog("close");
                    $(idN).dialog("open");
                }
                function cloDialog_g(id) {
                    var idN = "#dialog-g-" + id;
                    console.log(id);
                    console.log(idN);
                    $(idN).dialog("destroy");
                    $(idN).remove();
                    $(".cls-2").removeClass('g-active');
                }
                function minDialog(id) {
                    var idN = "#dialog-gemeente-" + id;
                    $(idN).dialog("close");
                }
                function cloDialog(id) {
                    var idN = "#dialog-gemeente-" + id;
                    var idBol = "#bol-" + id;
                    $(idN).dialog("destroy");
                    $(idN).remove();
                    $(idBol).remove();
                }
			</script>
		</div>
        <script>
            var allVals;
            function checkboxCheck() {
                allVals = [];
                $('input[name=gemeente]:checked').each(function() {
                    allVals.push($(this).val());
                });
            }
        </script>
		<script>
			$( function() {
				$( "#dialog-gemeente" ).dialog({
					draggable: false,
					modal: true,
					autoOpen: false
				});
				$(".ui-dialog-titlebar").hide();
			} );
			$( function() {
				$( ".dialog-gemeente-lijst" ).dialog({
					draggable: false,
					modal: true,
					autoOpen: false
				});
				$(".ui-dialog-titlebar").hide();
			} );
		</script>
	</body>
</html>