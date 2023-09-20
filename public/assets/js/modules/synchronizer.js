$(function () {
    $("#planGetData").on("click", function () {
        $.ajax({
            url: "public/api.php",
            beforeSend: function (xhr) {
                $(".overlay").show();
            },
            data: {
                module: "synchronizer",
                action: "getPlan"
            },
            type: "GET",
            dataType: "json",
        }).done(function (data) {
            $.each(data, function (key, value) {
                $("#planTable tr:last").after("<tr><td>" + value['group'] + "</td><td>" +
                    value['subject'] + "</td><td>" + value['user'] + "</td><td>" + value['1s'] + "</td><td>" +
                    value['2s'] + "</td><td>" + value['total'] + "</td></tr>");
            });
        })
            .fail(function (xhr, status, errorThrown) {

                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);

            })
            .always(function () {
                $(".overlay").hide();
            });
    });
    $("#timetableGetData").on("click", function () {
        $.ajax({
            url: "public/api.php",
            beforeSend: function (xhr) {
                $(".overlay").show();
            },
            data: {
                module: "synchronizer",
                action: "getTimeTable"
            },
            type: "GET",
            dataType: "json",
        }).done(function (data) {
            $.each(data, function (key, value) {
                $("#timetable tr:last").after("<tr><td>" + value['id'] + "</td><td>" + value['subject'] + "</td><td>" +
                    value['group'] + "</td><td>" + value['user'] + "</td><td>" + value['date'] + "</td>"+
                    "<td>" + value['start'] + "</td><td>" + value['end'] + "</td></tr>");
            });
        })
            .fail(function (xhr, status, errorThrown) {

                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);

            })
            .always(function () {
                $(".overlay").hide();
            });
    });
    $("#edboGetData").on("click", function () {

        $.ajax({
            url: "public/api.php",
            beforeSend: function (xhr) {
                $(".overlay").show();
            },
            data: {
                id: 2082,
                method: "edbo"
            },
            type: "GET",
            dataType: "json",
        })
            .done(function (data) {
                $(['university_id',
                    'university_name',
                    'university_short_name',
                    'university_name_en',
                    'university_type_name',
                    'university_financing_type_name',
                    'university_governance_type_name',
                    'university_director',
                    'university_email',
                    'university_site',
                    'registration_year']).each(function (key, value) {
                    // $("#" + value).html(data[value]);
                    tr = $("#" + value);
                    valDB = tr.find("td:eq(1)").text();

                    if (data[value] == valDB) {
                        tr.addClass("bg-success");
                    } else {
                        tr.addClass("bg-warning");
                        $("#edboSyncData").removeClass("disabled");

                    }
                    tr.find("td:eq(2)").html(data[value]);
                    tr.addClass("success");

                });
            })
            .fail(function (xhr, status, errorThrown) {
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            })
            .always(function () {
                $(".overlay").hide();
            });

    });
})