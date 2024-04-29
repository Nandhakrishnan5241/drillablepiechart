<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="../images/favicon.png">
    <title>Drillable Pie Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<style>
    body {
        font-family: Roboto, sans-serif;
        background-color: #34495e;
    }
    h2{
        color : whitesmoke;
    }

    #chart {
        max-width: 650px;
        margin-top: 100PX;
        margin-left: 400px;
        background-color: ##2c3e50;
    }
</style>

<body>
    <marquee behavior="scroll"  scrollamount="20" direction="left"><h2>Welcome to the Drillable Pie Chart</h2></marquee>
    {{-- {{ $decodeJSON['total'] }} --}}
    @php
        $machineGroup = [];
        foreach ($decodeJSON['List'] as $line => $machine) {
            $machineGroup[$line] = $machine['total'];
        }
        // print_r($machineGroup);
    @endphp
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
            series: <?php echo json_encode(array_values($machineGroup)); ?>,
            labels: <?php echo json_encode(array_keys($machineGroup)); ?>,

        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        function updateChart(currentPie) {

            var machinesArray = ["Tsugami", "Boroscope", "Robodrill"];
            console.log("currentpie", currentPie);
            var cluster = <?php echo $indexJSON; ?>;
            let machine = []
            if (machinesArray.includes(currentPie)) {
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
                var result = cluster['List'][currentPie.substring(0, currentPie.length - 2)]['machineList'][currentPie][
                    'days'
                ];
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
                // console.log("machine1", machine1);

                chart.updateOptions({
                    chart: {
                        type: "bar"
                    },

                    series: [{
                        name: currentPie,
                        data: machine
                    }],
                    dataLabels: {
                        enabled: true,
                        textAnchor: 'middle',
                        formatter: function(val, opts) {
                            return val
                        },
                        style: {
                            fontSize: '11px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            colors: [function(opts) {
                                return '#333'
                            }]
                        },
                        background: {
                            enabled: true,
                            foreColor: '#fff',
                            padding: 3,
                            borderRadius: 2,
                            borderWidth: 1,
                            borderColor: '#fff',
                            opacity: 0.9,
                            dropShadow: {
                                enabled: false,
                                top: 1,
                                left: 1,
                                color: '#000',
                                opacity: 0.45
                            }
                        },
                    },
                    tooltip: {
                        enabled: true
                    },
                    stroke: {
                        width: 1,
                        colors: ["#000"]
                    },
                    legend: {
                        show: false,
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

</body>

</html>
