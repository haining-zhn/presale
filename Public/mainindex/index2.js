/* DaTouWang URL: www.datouwang.com */
// Composite Chart

ChatDate=function (data) {
// ================================================================================
//     let reportCountList = [555, 222, 199, 287, 534, 709,
//         1179, 1256, 1632, 1856, 1850, 1850, 1850, 1850];
    //机房数据
    var reportCountList = data.reportCountList;
    //机房名称
    var CompositeLabels = data.CompositeLabels;

    var lineCompositeData = {
        labels: CompositeLabels,
        yMarkers: [
            {
                label: "数量20参考线",
                value: 20,
            }
        ],
        datasets: [{
            "name": "商机数",
            "values": reportCountList
        }]
    };
    var c1 = document.querySelector("#chart-composite-1");
    var c2 = document.querySelector("#chart-composite-2");

    var lineCompositeChart = new Chart(c1, {
        title: "当前区县商机数量（商机状态包含：见商机设置或下方产品标签，状态提示。）",
        data: lineCompositeData,
        type: 'line',
        height: 180,
        colors: ['green'],
        isNavigable: 1,
        valuesOverPoints: 1,

        lineOptions: {
            dotSize: 8
        },
    });

    lineCompositeChart.parent.addEventListener('data-select', function (e) {
        var name = e.label;
        /**
         * past请求实时数据
         *
         */
        $.post("",{name:name},function (data) {
            if(data){
                var barCompositeData = {
                        labels:data.labels ,
                        datasets: [
                            {
                                name: "已使用机柜数量 ",
                                values: data.used,
                            },
                            {
                                name: "空闲机柜数量 ",
                                values: data.fire,
                            },
                            {
                                name: "总机柜数量",
                                values: data.all
                            }
                        ]
                    };
                var barCompositeChart = new Chart(c2, {
                        data: barCompositeData,
                        type: 'bar',
                        height: 180,
                        colors: ['violet', 'light-blue', '#46a9f9'],
                        valuesOverPoints: 1,
                        axisOptions: {
                            xAxisMode: 'tick'
                        },
                        barOptions: {
                            stacked: 1
                        },

                    });
            }
        });
    });
}



