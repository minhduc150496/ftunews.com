<?php
if ( !defined('ABSPATH') )
    exit; // Shhh

global $poll;
$results = array();
$colors = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F' );
$fancy_colors = array( '#1abc9c', '#3498db', '#f39c12', '#8e44ad', '#34495e', '#bdc3c7', '#e74c3c', '#7f8c8d', '#19b5fe' );
foreach ( (array) get_poll_choices() as $index => $choice ):

    if ( isset($fancy_colors[$index]) ):
        $color = $fancy_colors[$index];
    else:
        $color = '#';
        for ( $i = 1; $i <= 6; $i++ ):
            $color .= $colors[array_rand($colors)];
        endfor;
    endif;


    $results[$index] = array(
        'value' => $choice->votes,
        'percentage' => $choice->votes_percentage,
        'color' => $color,
        'label' => empty($choice->text) ? empty($choice->label) ? '' : $choice->label
                    : $choice->text
    );
endforeach;
?>
<button type="button" class="button" id="tp-print-results"><?php _e('Print', 'tp-results-chart'); ?></button>
<canvas id="results-chart" width="250" height="250"></canvas>
<div id="tp-results-table" class="hide">
    <style type="text/css" media="print">
        body { 
            width:100% !important;
            margin:0 !important;
            padding:0 !important;
            line-height: 1.4;
            word-spacing:1.1pt;
            letter-spacing:0.2pt;
            font-family: Georgia, serif;
            color: #000;
            background: none;
            font-size: 12pt;
        }

        /* Table */
        table { margin: 1px; width: 100%; border-collapse: collapse !important; }
        th { border-bottom: 1px solid #333;  font-weight: bold; }
        td { border-bottom: 1px solid #333; }
        th,td { padding: 10px;border: 1px solid #ddd !important;text-align: center; }
        td.initial-align { text-align: initial; }
        caption { background: #fff; margin-bottom:2em; }
        thead {display: table-header-group;}
        tr {page-break-inside: avoid;}

        @page{margin:auto auto 0;}
    </style>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="75%"><?php _e('Choice', 'tp-results-chart'); ?></th>
                <th width="10%"><?php _e('Votes', 'tp-results-chart'); ?></th>
                <th width="10%">%</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $results as $index => $result ): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td class="initial-align"><?php echo $result['label']; ?></td>
                    <td><?php echo $result['value']; ?></td>
                    <td><?php echo $result['percentage']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style type="text/css">
    #tp-print-results {
        float: right;
    }
    .doughnut-legend {
        list-style: none;
        margin: 30px 0 0;
    }
    .doughnut-legend li {
        display: block;
        padding-left: 30px;
        position: relative;
        margin-bottom: 4px;
        border-radius: 5px;
        padding: 2px 8px 2px 28px;
        font-size: 14px;
        cursor: default;
        -webkit-transition: background-color 200ms ease-in-out;
        -moz-transition: background-color 200ms ease-in-out;
        -o-transition: background-color 200ms ease-in-out;
        transition: background-color 200ms ease-in-out;
    }
    .doughnut-legend li:hover {
        cursor: pointer;
        background-color: #fafafa;
    }
    .doughnut-legend li span {
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        width: 20px;
        height: 100%;
        border-radius: 5px;
    }
</style>
<script type="text/javascript">
    jQuery(function ($) {
        var helpers = Chart.helpers;
        var ctx = document.getElementById("results-chart").getContext("2d");
        var resultsChart = new Chart(ctx).Doughnut(<?php echo json_encode($results); ?>, {});
        var legendHolder = document.createElement('div');
        legendHolder.innerHTML = resultsChart.generateLegend();
        // Include a html legend template after the module doughnut itself
        helpers.each(legendHolder.firstChild.childNodes, function (legendNode, index) {
            helpers.addEvent(legendNode, 'mouseover', function () {
                var activeSegment = resultsChart.segments[index];
                activeSegment.save();
                activeSegment.fillColor = activeSegment.highlightColor;
                resultsChart.showTooltip([activeSegment]);
                activeSegment.restore();
            });
        });
        helpers.addEvent(legendHolder.firstChild, 'mouseout', function () {
            resultsChart.draw();
        });
        document.getElementById("results-chart").parentNode.appendChild(legendHolder.firstChild);

        $('#tp-print-results').on('click', function (e) {
            e.preventDefault();
            var printWindow = window.open('', false, 'width = 500, height = 500');

            $(printWindow.document.body).html($("#tp-results-table").html());
        });
    });
</script>