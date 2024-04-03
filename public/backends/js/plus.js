$(function () {
    $(".chart-legend .btn-depart").each(function () {
        var item = $(this).find("i.fa-circle");
        var value = $(this).find("span").attr("data-color");
        if (value == "30") item.addClass("text-primary");
        else if (value == "32") item.addClass("text-secondary");
        else if (value == "33") item.addClass("text-success");
        else if (value == "34") item.addClass("text-warning");
        else if (value == "39") item.addClass("text-info");
        else if (value == "40") item.addClass("text-muted-nvp");
        else if (value == "41") item.addClass("text-dark");
        else if (value == "44") item.addClass("text-red");
        else if (value == "45") item.addClass("text-success-nvp");
        else if (value == "46") item.addClass("text-warning-nvp");
        else if (value == "47") item.addClass("text-ngoai");
        else if (value == "48") item.addClass("text-ksnk");
        else if (value == "49") item.addClass("text-nhi");
    });

    if ($(".statistics-chart-canvas").length > 0) {
        /* Chart.js Charts */
        // Sales chart
        $(".statistics-chart-canvas").each(function () {
            var id = $(this).attr("id"),
                data_type = $(this).parent().attr("data-type")
                    ? $(this).parent().attr("data-type")
                    : "line",
                barW = $(this).parent().attr("data-width")
                    ? parseFloat($(this).parent().attr("data-width"))
                    : 0.9,
                data_label = $(this).parent().attr("label")
                    ? $(this).parent().attr("label")
                    : "Statistics",
                data_suffix = $(this).parent().attr("data-suffix")
                    ? $(this).parent().attr("data-suffix")
                    : "";
            var salesChartCanvas = document.getElementById(id).getContext("2d");
            var salesChartData = {
                labels: $.parseJSON(
                    $("#" + id)
                        .parent()
                        .attr("data-label")
                ),
                datasets: [
                    {
                        label: data_label,
                        backgroundColor: "rgba(60,141,188,0.9)",
                        borderColor: "rgba(60,141,188,0.8)",
                        pointRadius: false,
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: $.parseJSON(
                            $("#" + id)
                                .parent()
                                .attr("data")
                        ),
                    },
                ],
            };
            var salesChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [
                        {
                            gridLines: {
                                display: false,
                            },
                            barPercentage: barW,
                        },
                    ],
                    yAxes: [
                        {
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                beginAtZero: true,
                                userCallback: function (label, index, labels) {
                                    if (Math.floor(label) === label)
                                        return addCommas(label);
                                    else return addCommas(label);
                                },
                            },
                        },
                    ],
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            return (
                                data_label +
                                ": " +
                                addCommas(tooltipItem.yLabel) +
                                data_suffix
                            );
                        },
                    },
                },
                hover: {
                    mode: "index",
                    intersect: true,
                },
            };
            // This will get the first returned node in the jQuery collection.
            var salesChart = new Chart(salesChartCanvas, {
                type: data_type,
                data: salesChartData,
                options: salesChartOptions,
            });
        });
    }

    if ($(".doughnut-canvas").length > 0) {
        $(".doughnut-canvas").each(function () {
            // Donut Chart
            var id = $(this).attr("id");
            var pieChartCanvas = document.getElementById(id).getContext("2d");
            var pieData = {
                labels: $(this).parent().attr("data-label").split(","),
                datasets: [
                    {
                        data: $(this).parent().attr("data").split(","),
                        backgroundColor: $(this)
                            .parent()
                            .attr("data-colors")
                            .split(","),
                    },
                ],
            };
            var pieOptions = {
                legend: {
                    display: true,
                },
                maintainAspectRatio: false,
                responsive: true,
            };
            var pieChart = new Chart(pieChartCanvas, {
                type: "doughnut",
                data: pieData,
                options: pieOptions,
            });
        });
    }

    //Tổng thiết bị theo khoa
    if ($("#sales-chart").length > 0) {
        var ticksStyle = {
            fontColor: "#495057",
            fontStyle: "bold",
        };

        var mode = "index";
        var intersect = true;

        var $salesChart = $("#sales-chart");
        // eslint-disable-next-line no-unused-vars
        var salesChart = new Chart($salesChart, {
            type: "bar",
            data: {
                //labels: ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
                labels: $("#sales-chart").attr("data-label").split(","),
                datasets: [
                    {
                        backgroundColor: [
                            "#007bff",
                            "#6c757d",
                            "#28a745",
                            "#ffc107",
                            "#17a2b8",
                            "#ff8100",
                            "#343a40",
                            "#dc3545",
                            "#00ffd0",
                            "#ff00f7",
                            "#008b8b",
                            "#8400ff",
                            "#a52a2a",
                            "#88b530",
                            "#38b530",
                            "#88b530",
                            "#48b530",
                            "#1bd6b6",
                            "#3b673d",
                            "#c71515",
                            "#15c71c",
                            "#102911",
                            "#87cc0e",
                            "#0b32b1",
                            "#292e25",
                            "#e73c6c",
                            "#0b929e",
                            "#b1b6a6",
                            "#1b0b06",
                            "#242b1e",
                            "#160c4b",
                            "#1c2724",
                            "#d3d38e",
                            "#122088",
                            "#09420b",
                            "#27271d",
                            "#46dbb8",
                            "#070e03",
                            "#0c0e03",
                            "#1a1acc",
                        ],
                        borderColor: [
                            "#007bff",
                            "#6c757d",
                            "#28a745",
                            "#ffc107",
                            "#17a2b8",
                            "#ff8100",
                            "#343a40",
                            "#dc3545",
                            "#00ffd0",
                            "#ff00f7",
                            "#008b8b",
                            "#8400ff",
                            "#a52a2a",
                            "#88b530",
                            "#38b530",
                            "#88b530",
                            "#48b530",
                            "#1bd6b6",
                            "#3b673d",
                            "#c71515",
                            "#15c71c",
                            "#102911",
                            "#87cc0e",
                            "#0b32b1",
                            "#292e25",
                            "#e73c6c",
                            "#0b929e",
                            "#b1b6a6",
                            "#1b0b06",
                            "#242b1e",
                            "#160c4b",
                            "#1c2724",
                            "#d3d38e",
                            "#122088",
                            "#09420b",
                            "#27271d",
                            "#46dbb8",
                            "#070e03",
                            "#0c0e03",
                            "#1a1acc",
                        ],
                        data: $("#sales-chart").attr("data").split(","),
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect,
                },
                hover: {
                    mode: mode,
                    intersect: intersect,
                },
                legend: {
                    display: false,
                },
                scales: {
                    yAxes: [
                        {
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: "4px",
                                color: "rgba(0, 0, 0, .2)",
                                zeroLineColor: "transparent",
                            },
                            ticks: $.extend(
                                {
                                    beginAtZero: true,

                                    // Include a dollar sign in the ticks
                                    callback: function (value) {
                                        if (value >= 1000) {
                                            value /= 1000;
                                            value += "k";
                                        }

                                        return value;
                                    },
                                },
                                ticksStyle
                            ),
                        },
                    ],
                    xAxes: [
                        {
                            display: true,
                            gridLines: {
                                display: false,
                            },
                            ticks: ticksStyle,
                        },
                    ],
                },
            },
        });
    }
    // Tổng thiết bị đang báo hỏng theo khoa
    if ($("#wasbrokenChart").length > 0) {
        var pieChartCanvas = $("#wasbrokenChart").get(0).getContext("2d");
        var pieData = {
            labels: $("#wasbrokenChart").attr("data-label").split(","),
            datasets: [
                {
                    data: $("#wasbrokenChart").attr("data").split(","),
                    backgroundColor: [
                        "#007bff",
                        "#6c757d",
                        "#28a745",
                        "#ffc107",
                        "#17a2b8",
                        "#ff8100",
                        "#343a40",
                        "#dc3545",
                        "#00ffd0",
                        "#ff00f7",
                        "#008b8b",
                        "#8400ff",
                        "#a52a2a",
                        "#88b530",
                        "#38b530",
                        "#88b530",
                        "#48b530",
                        "#1bd6b6",
                        "#3b673d",
                        "#c71515",
                        "#15c71c",
                        "#102911",
                        "#87cc0e",
                        "#0b32b1",
                        "#292e25",
                        "#e73c6c",
                        "#0b929e",
                        "#b1b6a6",
                        "#1b0b06",
                        "#242b1e",
                        "#160c4b",
                        "#1c2724",
                        "#d3d38e",
                        "#122088",
                        "#09420b",
                        "#27271d",
                        "#46dbb8",
                        "#070e03",
                        "#0c0e03",
                        "#1a1acc",
                    ],
                },
            ],
        };
        console.log(pieData);
        var pieOptions = {
            legend: {
                display: false,
            },
        };
        var wasbrokenChart = new Chart(pieChartCanvas, {
            type: "doughnut",
            data: pieData,
            options: pieOptions,
        });
    }
    // Tổng thiết bị đang sửa chữa theo khoa
    if ($("#correctedChart").length > 0) {
        var pieChartCanvas = $("#correctedChart").get(0).getContext("2d");
        var pieData = {
            labels: $("#correctedChart").attr("data-label").split(","),
            datasets: [
                {
                    data: $("#correctedChart").attr("data").split(","),
                    backgroundColor: [
                        "#007bff",
                        "#6c757d",
                        "#28a745",
                        "#ffc107",
                        "#17a2b8",
                        "#ff8100",
                        "#343a40",
                        "#dc3545",
                        "#00ffd0",
                        "#ff00f7",
                        "#008b8b",
                        "#8400ff",
                        "#a52a2a",
                        "#88b530",
                        "#38b530",
                        "#88b530",
                        "#48b530",
                        "#1bd6b6",
                        "#3b673d",
                        "#c71515",
                        "#15c71c",
                        "#102911",
                        "#87cc0e",
                        "#0b32b1",
                        "#292e25",
                        "#e73c6c",
                        "#0b929e",
                        "#b1b6a6",
                        "#1b0b06",
                        "#242b1e",
                        "#160c4b",
                        "#1c2724",
                        "#d3d38e",
                        "#122088",
                        "#09420b",
                        "#27271d",
                        "#46dbb8",
                        "#070e03",
                        "#0c0e03",
                        "#1a1acc",
                    ],
                },
            ],
        };
        var pieOptions = {
            legend: {
                display: false,
            },
        };
        var correctedChart = new Chart(pieChartCanvas, {
            type: "doughnut",
            data: pieData,
            options: pieOptions,
        });
    }
    // Multi upload by dropzone
    if ($("#multiDz").length > 0) {
        $("#multiDz").dropzone({
            autoProcessQueue: false,
            uploadMultiple: true,
            addRemoveLinks: true,
            dictRemoveFile: "Remove",
            thumbnailHeight: 150,
            thumbnailWidth: 100,
            parallelUploads: 10,
            maxFiles: 10,
            // previewTemplate: $('#template-preview').html(),
            url: $("#multiDz").attr("data-action"),
            headers: {
                "x-csrf-token": $('input[name="_token"]').val(),
            },
            // The setting up of the dropzone
            init: function () {
                var multiDropzone = this;
                var cat_ids = new Array();
                $('.library-op form button[type="submit"]').on(
                    "click",
                    function (e) {
                        // Make sure that the form isn't actually being sent.
                        e.preventDefault();
                        e.stopPropagation();
                        if (multiDropzone.files != "") {
                            $("#category").val("");
                            $("#addFileMulti .checkbox").each(function () {
                                if ($(this).find("input").is(":checked")) {
                                    cat_ids.push($(this).find("input").val());
                                }
                            });
                            $("#category").val(cat_ids.toString());
                            $('input[name="category"]').val(cat_ids);

                            multiDropzone.processQueue();
                        } else {
                            $("#multiDz").submit();
                        }
                    }
                );
                this.on("sending", function (file, xhr, formData) {
                    formData.append(
                        "category",
                        $('input[name="category"]').val()
                    );
                });
                this.on("sendingmultiple", function (files) {
                    // Gets triggered when the form is actually being sent.
                    // Hide the success button or the complete form.
                });
                this.on("successmultiple", function (files, response) {
                    multiDropzone.removeFile(files);
                    current_form = $("#mediaMulti form");
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        type: "POST",
                        url: current_form.attr("data-action"),
                        cache: false,
                        data: {
                            _token: _token,
                            catId: "",
                            s: "",
                            chosen: $('input[name="attachment"]').val(),
                        },
                        success: function (data) {
                            if (data.message != "error") {
                                current_form
                                    .find(".list-media")
                                    .html(data.html);
                                current_form.find(".limit").val(data.limit);
                                current_form.find(".current").val(data.current);
                                current_form.find(".total").val(data.total);
                            }
                        },
                    });
                    // $('#multiDz').closest('form').submit();
                });
                this.on("errormultiple", function (files, response) {
                    // Gets triggered when there was an error sending the files.
                    // Maybe show form again, and notify user of error
                });
            },
        });
    }
});
// Dropzone.autoDiscover = false;
function addCommas(nStr) {
    nStr += "";
    x = nStr.split(".");
    x1 = x[0];
    x2 = x.length > 1 ? "." + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "," + "$2");
    }
    return x1 + x2;
}
