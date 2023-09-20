$(function () {
    $("button.mark").click(function () {
        let lessonId = $(this).parents("table").attr("id");
        let tr = $(this).parents("tr");
        let student = tr.attr("id");
        let btn = $(this);

        $.ajax({
            url: "public/api.php",
            beforeSend: function (xhr) {
                $(".overlay").show();
            },
            data: {
                module: "workflow",
                action: "giveRating",
                lid: lessonId,
                student: student,
                mark: $(this).attr("data-mark")
            },
            type: "GET",
            dataType: "json",
        })
            .done(function (data) {
                if(data !== 'null') {
                    if (data >= 0) {

                        $("#" + student + " .mark-place").html(data);
                        $("#" + student + " button.missing").removeClass("active");
                        $("#" + student + " .table-avatar").removeClass("border-danger border-gray").addClass("border-success");

                    } else {
                        $("#" + student + " .table-avatar").removeClass("border-success border-gray").addClass("border-danger");
                        $("#" + student + " .mark-place").html("Н");
                        btn.addClass("active");
                    }
                }else{
                    $("#" + student + " button.missing").removeClass("active");
                    $("#" + student + " .mark-place").html("&nbsp;-&nbsp;");
                    $("#" + student + " .table-avatar").removeClass("border-danger border-success").addClass("border-gray");
                }


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