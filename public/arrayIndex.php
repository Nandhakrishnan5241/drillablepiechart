<?php
$file           = "downtime.json";
$indexJSON  = file_get_contents($file);
$decodeJSON = json_decode($indexJSON, true);
echo "<pre>";
//print_r($decodeJSON);die;
$machineGroup = array();
foreach ($decodeJSON['List'] as $line => $machine) {
    $machineGroup[$line] = $machine['total'];
}
print_r($machineGroup);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>array Index</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<style>
    body {
        font-family: Roboto, sans-serif;
    }

    #chart {
        max-width: 650px;
        margin-top: -200px;
        margin-left: 400px;

    }
</style>

<body>
    <div id="chart">
    </div>

    <script>
        var options = {
            chart: {
                type: 'pie',
                events: {
                    click: function(event, chartContext, config) {
                        console.log("event", event);
                        var i = event.target.parentElement.getAttribute("data:realIndex");
                        var currentPie = config.config.labels[i];
                        console.log(currentPie);
                        updateChart(currentPie);
                    }
                },
            },
            legend: {
                position: 'bottom'
            },
            series: <?php echo json_encode(array_values($machineGroup)) ?>,
            labels: <?php echo json_encode(array_keys($machineGroup)) ?>,

        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        function updateChart(currentPie) {


            var machinesArray = ["Tsugami", "Boroscope", "Robodrill"];
            console.log("currentpie", currentPie);
            var cluster = <?php echo $indexJSON ?>;
            let machine = []
            if (machinesArray.includes(currentPie)) {
                console.log("yes");
                var result = cluster['List'][currentPie]['machineList'];
                console.log("result", result);

                for (let i in result) {
                    console.log(result[i]);
                    machine[i] = result[i]['total'];
                }

                chart.updateOptions({
                    series: Object.values(machine),
                    labels: Object.keys(machine)
                });
            } else {
                console.log("else");
                var result = cluster['List'][currentPie.substring(0, currentPie.length - 2)]['machineList'][currentPie]['days'];
                console.log("result", result);
                for (let i in result) {
                    const innerArray = [];
                    innerArray[0] = parseInt(i);
                    innerArray[1] = result[i];
                    console.log(innerArray);
                    //return false;
                    machine.push(innerArray);
                }
                console.log("machine", machine);
                var machine1 = [
                    [1672531200000, 34],
                    [1672617600000, 43],
                    [1672704000000, 31],
                    [1672790400000, 43]
                ];
                console.log("machine1", machine1);

                chart.updateOptions({
                    chart: {
                        type: "bar"
                    },

                    series: [{
                        name: currentPie,
                        data: machine
                    }],
                    dataLabels: {
                        enabled: false,
                        textAnchor: 'end'
                    },
                    tooltip : {
                        enabled : false
                    },
                    stroke: {
                        width: 1,
                        colors: ["#000"]
                    },
                    legend: {
                        show: true,
                        position: 'bottom'
                    },
                    xaxis: {
                        type: "datetime"
                    }
                });
            }
            console.log("machine", machine);
        }
    </script>